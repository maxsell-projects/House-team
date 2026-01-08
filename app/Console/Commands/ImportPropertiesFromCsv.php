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

class ImportPropertiesFromCsv extends Command
{
    // Ajustei a assinatura para aceitar o nome do arquivo que você está usando
    protected $signature = 'properties:import-csv {file=Properties-Export-2026-January-07-2241_only-publish_UPDATED-agents-names.csv}';
    protected $description = 'Importa imóveis do CSV Real Homes corrigindo mapeamento de colunas e galeria.';

    public function handle()
    {
        $fileName = $this->argument('file');
        $path = storage_path('app/' . $fileName);

        if (!file_exists($path)) {
            $this->error("❌ Arquivo não encontrado em: {$path}");
            return 1;
        }

        $adminUser = User::first();
        if (!$adminUser) {
            $this->error('❌ Crie um usuário no banco primeiro!');
            return 1;
        }

        $this->info("🚀 Iniciando importação para o CSV Real Homes...");

        $file = fopen($path, 'r');
        $headers = fgetcsv($file);
        $headerMap = array_flip(array_map('trim', $headers));

        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            $getVal = fn($key) => isset($headerMap[$key]) ? ($row[$headerMap[$key]] ?? null) : null;

            // Mapeamento correto para a sua planilha 
            $reference = $getVal('REAL_HOMES_property_id'); 
            $title = $getVal('Title');

            if (empty($reference)) continue;

            if (Property::where('crm_code', $reference)->exists()) {
                $this->warn("⚠️  {$reference} já existe. Pulando.");
                continue;
            }

            $this->info("🔧 Processando: {$title}");

            // Tratamento de Tipo
            $propertyTypes = Str::lower($getVal('Property Types'));
            $type = 'flat';
            if (Str::contains($propertyTypes, ['moradia', 'house', 'quinta'])) $type = 'house';
            if (Str::contains($propertyTypes, ['terreno', 'land'])) $type = 'land';
            if (Str::contains($propertyTypes, ['loja', 'comercial', 'armazém'])) $type = 'commercial';

            // Consultor 
            $agentName = $getVal('REAL_HOMES_agents');
            $consultant = $agentName ? Consultant::where('name', 'LIKE', "%{$agentName}%")->first() : null;

            // Download da Capa (Image Featured) 
            $rawCoverUrl = $getVal('Image Featured');
            $coverPath = $rawCoverUrl ? $this->downloadImage($rawCoverUrl, $reference, 'cover') : null;

            try {
                $property = Property::create([
                    'user_id' => $adminUser->id,
                    'consultant_id' => $consultant?->id,
                    'title' => $title,
                    'slug' => Str::slug($title . '-' . $reference),
                    'description' => $getVal('Content'), // Na sua planilha a descrição está em 'Content' 
                    'price' => (float) $getVal('REAL_HOMES_property_price'),
                    'location' => $getVal('REAL_HOMES_property_address'),
                    'type' => $type,
                    'status' => 'active',
                    'bedrooms' => (int) $getVal('REAL_HOMES_property_bedrooms'),
                    'bathrooms' => (int) $getVal('REAL_HOMES_property_bathrooms'),
                    'area_gross' => (float) $getVal('REAL_HOMES_property_size'),
                    'energy_rating' => $getVal('REAL_HOMES_energy_class'),
                    'crm_code' => $reference,
                    'cover_image' => $coverPath,
                ]);

                // Galeria: Na sua planilha as fotos estão em 'Image URL' separadas por '|' 
                $galleryRaw = $getVal('Image URL');
                if ($galleryRaw) {
                    $urls = explode('|', $galleryRaw);
                    $order = 1;
                    foreach ($urls as $url) {
                        $url = trim($url);
                        if (empty($url) || $url === $rawCoverUrl) continue; // Pula se for vazio ou se for a mesma da capa

                        $imgPath = $this->downloadImage($url, $reference, "gallery_{$order}");
                        if ($imgPath) {
                            PropertyImage::create([
                                'property_id' => $property->id,
                                'path' => $imgPath,
                                'order' => $order++
                            ]);
                        }
                    }
                }
                $count++;
            } catch (\Exception $e) {
                $this->error("❌ Erro no imóvel {$reference}: " . $e->getMessage());
            }
        }

        fclose($file);
        $this->info("✅ Finalizado! {$count} imóveis importados com sucesso.");
        return 0;
    }

    private function downloadImage($url, $reference, $suffix)
    {
        try {
            $response = Http::timeout(15)->get($url);
            if ($response->failed()) return null;

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = "properties/{$reference}/{$reference}_{$suffix}.{$extension}";

            Storage::disk('public')->put($filename, $response->body());
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}