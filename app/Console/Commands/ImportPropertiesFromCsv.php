<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use App\Models\Consultant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImportPropertiesFromCsv extends Command
{
    protected $signature = 'properties:import-csv {file=Main (10).csv}';
    protected $description = 'Importa imóveis corrigindo duplicidade de fotos e dados de quartos/terrenos.';

    public function handle()
    {
        $fileName = $this->argument('file');
        $path = storage_path('app/' . $fileName);

        if (!file_exists($path)) {
            $this->error("❌ Arquivo não encontrado: {$path}");
            return 1;
        }

        $adminUser = User::first();
        if (!$adminUser) {
            $this->error('❌ Crie um usuário admin primeiro!');
            return 1;
        }

        $this->info("🚀 Iniciando importação blindada...");

        $file = fopen($path, 'r');
        $headers = fgetcsv($file);
        $headers = array_map('trim', $headers);
        $headerMap = array_flip($headers);

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            $getVal = fn($key) => isset($headerMap[$key]) ? ($row[$headerMap[$key]] ?? null) : null;

            $reference = $getVal('ID'); 
            $title = $getVal('Title');
            if (empty($reference)) continue;

            // Evita duplicidade de imóvel
            if (Property::where('crm_code', $reference)->exists()) {
                $this->warn("⚠️  {$reference} já existe. Pulando.");
                continue;
            }

            $this->info("🔧 Processando: {$title}");

            // --- 1. DETECÇÃO DE TIPO (PRIORITÁRIA) ---
            $rawType = Str::lower($getVal('Type') . ' ' . $title); // Junta Type + Title para ser mais assertivo
            $type = match(true) {
                Str::contains($rawType, ['terreno', 'lote', 'land', 'plot']) => 'land',
                Str::contains($rawType, ['loja', 'comercial', 'store', 'armalém', 'escritório', 'office']) => 'commercial',
                Str::contains($rawType, ['garagem', 'garage', 'estacionamento', 'parking']) => 'garage',
                Str::contains($rawType, ['moradia', 'casa', 'vivenda', 'villa', 'chalet', 'quinta', 'herdade']) => 'house',
                default => 'flat',
            };

            // --- 2. SANITIZAÇÃO DE NÚMEROS (QUARTOS/WC) ---
            // Pega apenas o PRIMEIRO número encontrado na string (ex: "3 a 4" -> 3)
            $bedroomsRaw = $this->extractFirstNumber($getVal('Bedroom'));
            $bathroomsRaw = $this->extractFirstNumber($getVal('Bathroom'));

            // REGRA DE OURO: Se for Terreno/Comercial/Garagem, quartos = 0
            if (in_array($type, ['land', 'commercial', 'garage'])) {
                $bedrooms = 0;
                $bathrooms = 0;
            } else {
                // SANITY CHECK: Se tiver mais de 50 quartos, provavelmente é bug (pegou a área ou ano)
                $bedrooms = ($bedroomsRaw > 50) ? 0 : $bedroomsRaw;
                $bathrooms = ($bathroomsRaw > 50) ? 0 : $bathroomsRaw;
            }

            // Tratamento de Preço e Área
            $price = (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', str_replace('.', '', $getVal('Price')))); // Remove símbolos, troca vírgula por ponto
            // Se o preço ficar 0 ou absurdo, tenta limpar só os nao-numericos
            if ($price < 100) {
                 $price = (float) preg_replace('/[^0-9]/', '', $getVal('Price'));
            }

            $area = (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $getVal('Area')));

            // Consultor
            $agentName = $getVal('Agent');
            $consultant = $agentName ? Consultant::where('name', 'LIKE', "%{$agentName}%")->first() : null;

            // --- 3. IMAGENS (COM FILTRO DE DUPLICIDADE) ---
            $uniqueImages = [];
            $rawCoverUrl = $getVal('Main Image');
            $coverPath = null;

            // Baixa a capa
            if ($rawCoverUrl && filter_var($rawCoverUrl, FILTER_VALIDATE_URL)) {
                $coverPath = $this->downloadImage($rawCoverUrl, $reference, 'cover');
                if ($coverPath) $uniqueImages[] = $rawCoverUrl; // Registra que já baixamos essa URL
            }

            // Cria o imóvel
            try {
                $property = new Property();
                $property->user_id = $adminUser->id;
                $property->consultant_id = $consultant?->id;
                $property->title = substr($title, 0, 255);
                $property->slug = Str::slug($title . '-' . $reference);
                $property->description = $getVal('Description') ?? '';
                $property->price = $price;
                $property->location = $getVal('Location') ?? 'Lisboa';
                $property->type = $type;
                $property->status = 'active';
                $property->bedrooms = $bedrooms;
                $property->bathrooms = $bathrooms;
                $property->area_gross = $area;
                $property->energy_rating = $getVal('Energy Class');
                $property->crm_code = $reference;
                $property->cover_image = $coverPath;
                $property->save();

                // Loop Galeria Inteligente
                $order = 1;
                foreach ($headerMap as $headerName => $index) {
                    // Procura colunas de imagem (Image URL X, Image X, etc)
                    if (Str::contains($headerName, ['Image', 'Foto']) && $headerName !== 'Main Image') {
                        $imgUrl = $row[$index] ?? null;

                        // Validações
                        if (!$imgUrl) continue;
                        if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) continue;
                        if (in_array($imgUrl, $uniqueImages)) continue; // 🚫 PULA SE JÁ EXISTE

                        // Baixa e Salva
                        $imgPath = $this->downloadImage($imgUrl, $reference, "gallery_{$order}");
                        if ($imgPath) {
                            PropertyImage::create([
                                'property_id' => $property->id,
                                'path' => $imgPath,
                                'order' => $order++
                            ]);
                            $uniqueImages[] = $imgUrl; // Adiciona à lista de processadas
                        }
                    }
                }
                $count++;

            } catch (\Exception $e) {
                $this->error("❌ Falha no imóvel {$reference}: " . $e->getMessage());
            }
        }
        
        fclose($file);
        $this->info("✅ Processo finalizado! {$count} imóveis importados corretamente.");
        return 0;
    }

    // Helper para extrair "3" de "3 a 4" ou "3 quartos"
    private function extractFirstNumber($string) {
        if (empty($string)) return 0;
        preg_match('/\d+/', $string, $matches);
        return isset($matches[0]) ? (int)$matches[0] : 0;
    }

    private function downloadImage($url, $reference, $suffix)
    {
        try {
            $response = Http::timeout(5)->get($url); // 5s timeout (mais rápido)
            if ($response->failed()) return null;

            // Detecta extensão
            $mime = $response->header('Content-Type');
            $ext = match($mime) {
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg'
            };

            $filename = "properties/{$reference}/{$reference}_{$suffix}.{$ext}";
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}