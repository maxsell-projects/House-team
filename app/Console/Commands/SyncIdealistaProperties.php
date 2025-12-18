<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IdealistaService;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SyncIdealistaProperties extends Command
{
    protected $signature = 'idealista:sync {--page=1 : Página inicial} {--limit=50 : Itens por página}';
    protected $description = 'Sincroniza imóveis da API Partner do Idealista para o banco local';

    public function handle(IdealistaService $service)
    {
        $this->info('A conectar à API do Idealista (Partner v1)...');

        try {
            $page = (int) $this->option('page');
            $size = (int) $this->option('limit');

            // 1. Pega os headers de autenticação para usar nas imagens depois
            $authHeaders = $service->getHeaders();
            // Remove Content-Type pois no GET de imagem não enviamos JSON
            unset($authHeaders['Content-Type']);

            $response = $service->getProperties($page, $size);
            
            $listings = $response['properties'] ?? $response['elementList'] ?? $response['items'] ?? [];
            if (empty($listings) && isset($response[0])) $listings = $response;

            if (empty($listings)) {
                $this->warn('Nenhum imóvel encontrado nesta página.');
                return;
            }

            $count = 0;
            $updated = 0;

            $this->output->progressStart(count($listings));

            foreach ($listings as $data) {
                $idealistaId = $data['propertyId'] ?? $data['propertyCode'] ?? $data['id'] ?? null;
                if (!$idealistaId) continue;

                $features = $data['features'] ?? [];
                $operation = $data['operation'] ?? [];
                $address = $data['address'] ?? [];

                $typeLabel = $this->mapType($data['type'] ?? 'flat');
                $statusLabel = $this->mapStatus($operation['type'] ?? 'sale');
                
                $city = $address['town'] ?? $address['municipality'] ?? 'Localização';
                $rooms = $features['rooms'] ?? 0;
                $typology = $rooms > 0 ? "T{$rooms}" : "";
                
                $generatedTitle = trim("{$typeLabel} {$typology} em {$city}");
                if (isset($data['reference']) && !empty($data['reference'])) {
                    $generatedTitle .= " (Ref: {$data['reference']})";
                }

                $addressText = $address['streetName'] ?? '';
                if (isset($address['streetNumber'])) {
                    $addressText .= ', ' . $address['streetNumber'];
                }

                $baseSlug = Str::slug($generatedTitle . '-' . $idealistaId);
                
                $propertyData = [
                    'title'          => $generatedTitle,
                    'slug'           => $baseSlug,
                    'price'          => $operation['price'] ?? 0,
                    'location'       => $city,
                    'city'           => $city,
                    'address'        => $addressText,
                    'postal_code'    => $address['postalCode'] ?? null,
                    'bedrooms'       => $rooms,
                    'bathrooms'      => $features['bathroomNumber'] ?? 0,
                    'area_gross'     => $features['areaConstructed'] ?? 0,
                    'description'    => $this->getLocalizedDescription($data['descriptions'] ?? []),
                    'has_pool'       => $features['pool'] ?? false,
                    'has_garden'     => $features['garden'] ?? false,
                    'has_lift'       => $features['liftAvailable'] ?? false,
                    'has_terrace'    => $features['terrace'] ?? false,
                    'has_air_conditioning' => $features['conditionedAir'] ?? false,
                    'status'         => $statusLabel,
                    'type'           => $typeLabel,
                    'idealista_url'  => $data['additionalLink'] ?? "https://www.idealista.pt/imovel/{$idealistaId}/",
                    'is_visible'     => true, 
                    'last_synced_at' => now(),
                ];

                try {
                    $property = Property::updateOrCreate(['idealista_id' => $idealistaId], $propertyData);
                } catch (\Illuminate\Database\QueryException $e) {
                    if (str_contains($e->getMessage(), 'properties_slug_unique')) {
                        $propertyData['slug'] = $baseSlug . '-' . rand(100, 999);
                        $property = Property::updateOrCreate(['idealista_id' => $idealistaId], $propertyData);
                    } else {
                        throw $e;
                    }
                }

                // --- IMPORTAR IMAGENS (COM AUTH) ---
                $imagesData = $data['images'] ?? $service->getPropertyImages($idealistaId);
                
                if (!empty($imagesData)) {
                    // Passamos os headers de autenticação para o método de download
                    $this->syncImages($property, $imagesData, $authHeaders);
                }

                if ($property->wasRecentlyCreated) {
                    $count++;
                } else {
                    $updated++;
                }

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->table(['Novos', 'Atualizados'], [[$count, $updated]]);

        } catch (\Exception $e) {
            $this->error('Erro crítico: ' . $e->getMessage());
            Log::error('Idealista Sync Critical Error: ' . $e->getMessage());
        }
    }

    /**
     * Processa e baixa as imagens usando Autenticação
     */
    private function syncImages(Property $property, array $imagesData, array $authHeaders)
    {
        $coverSet = false;

        foreach ($imagesData as $imgData) {
            $url = $imgData['url'] ?? null;
            if (!$url) continue;

            $filename = 'idealista_' . $property->id . '_' . md5($url) . '.jpg';
            $path = 'properties/gallery/' . $filename;

            // Só baixa se não existir ou se o arquivo existente for inválido (0 bytes ou muito pequeno)
            $exists = Storage::disk('public')->exists($path);
            $size = $exists ? Storage::disk('public')->size($path) : 0;

            if (!$exists || $size < 500) { // Menor que 500 bytes é certeza que é erro
                try {
                    // Tenta baixar COM a autenticação do Idealista
                    $response = Http::withHeaders($authHeaders)
                        ->withHeaders(['User-Agent' => 'Mozilla/5.0']) // User agent + Auth
                        ->get($url);

                    if ($response->successful()) {
                        Storage::disk('public')->put($path, $response->body());
                    } else {
                        // Tenta sem autenticação (caso seja URL pública de CDN)
                        $responsePublic = Http::get($url);
                        if ($responsePublic->successful()) {
                            Storage::disk('public')->put($path, $responsePublic->body());
                        } else {
                            Log::warning("Falha download imagem {$property->id}: " . $response->status());
                            continue;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Exceção imagem {$url}: " . $e->getMessage());
                    continue;
                }
            }

            // Confirma se o arquivo foi salvo corretamente antes de registar no banco
            if (Storage::disk('public')->exists($path) && Storage::disk('public')->size($path) > 500) {
                $existingImage = PropertyImage::where('property_id', $property->id)->where('path', $path)->first();
                
                if (!$existingImage) {
                    PropertyImage::create(['property_id' => $property->id, 'path' => $path]);
                }

                if (!$coverSet) {
                    if (!$property->cover_image || !Storage::disk('public')->exists($property->cover_image)) {
                        $property->update(['cover_image' => $path]);
                    }
                    $coverSet = true;
                }
            }
        }
    }

    private function getLocalizedDescription(array $descriptions)
    {
        if (empty($descriptions)) return '';
        foreach ($descriptions as $desc) {
            if (isset($desc['language']) && $desc['language'] === 'pt' && isset($desc['text'])) {
                return $desc['text'];
            }
        }
        if (isset($descriptions['text'])) return $descriptions['text'];
        return $descriptions[0]['text'] ?? '';
    }

    private function mapStatus($operationType)
    {
        return match (strtolower($operationType)) {
            'rent' => 'Arrendamento',
            'sale' => 'Venda',
            default => 'Venda',
        };
    }

    private function mapType($idealistaType)
    {
        return match (strtolower($idealistaType)) {
            'flat', 'penthouse', 'duplex', 'studio' => 'Apartamento',
            'chalet', 'house', 'countryhouse' => 'Moradia',
            'land' => 'Terreno',
            'office', 'commercial', 'premises' => 'Comercial',
            'garage' => 'Garagem',
            'building' => 'Prédio',
            default => 'Apartamento',
        };
    }
}