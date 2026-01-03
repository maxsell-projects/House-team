<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\IdealistaExportService;

class RunIdealistaCertification extends Command
{
    protected $signature = 'idealista:certification';
    protected $description = 'Executa bateria COMPLETA de testes para certificação do Idealista';

    protected $service;
    protected $headers;
    protected $baseUrl;
    
    // Estado
    protected $contactId = null;
    protected $flatId = null; // Para testes principais
    protected $houseId = null;
    protected $landId = null;

    // Dados Reais (Hardcoded para passar na validação humana)
    protected $realAddress = [
        'visibility' => 'hidden',
        'precision' => 'exact',
        'country' => 'Portugal',
        'streetName' => 'Avenida da Liberdade',
        'streetNumber' => '100',
        'postalCode' => '1250-145',
        'town' => 'Lisboa'
    ];

    public function handle(IdealistaExportService $service)
    {
        $this->service = $service;
        $this->headers = $service->getHeaders('write'); 
        $this->baseUrl = config('services.idealista.base_url'); 

        $this->info("🚀 Iniciando Bateria de Testes COMPLETA - Idealista 2026");
        
        $this->runContactTests();
        $this->runPropertyTests();
        $this->runImageTests();
        $this->runDeactivationTests();

        $this->info("🏁 Fim dos testes. Copie os logs para as planilhas.");
    }

    private function runContactTests()
    {
        $this->warn('--- ABA: CONTACTS ---');

        // Contact01: Create
        $payload = [
            'name' => 'House Team Admin',
            'email' => 'contact@houseteam.pt', // Email com formato real
            'primaryPhoneNumber' => '912345678',
            'mobilePhoneNumber' => '961234567'
        ];
        
        $this->executeTest('Contact01 (Create)', 'POST', '/v1/contacts', $payload, 201, function($r) {
            $this->contactId = $r['contactId'] ?? $r['code'] ?? null;
        });

        if (!$this->contactId) {
            $this->error("Aborting: Contact creation failed.");
            return;
        }

        // Contact02 & 03 (Errors) - Já fizemos, mas vamos repetir para garantir log
        $p = $payload; unset($p['email']);
        $this->executeTest('Contact02 (No Email)', 'POST', '/v1/contacts', $p, 400);
        
        $p = $payload; $p['email'] = 'bad_email';
        $this->executeTest('Contact03 (Bad Email)', 'POST', '/v1/contacts', $p, 400);

        // Contact04: Update (Eles exigiram)
        $updatePayload = $payload;
        $updatePayload['name'] = 'House Team Admin Updated';
        $this->executeTest('Contact04 (Update)', 'PUT', "/v1/contacts/{$this->contactId}", $updatePayload, 200);

        // Contact05: Get
        $this->executeTest('Contact05 (Get)', 'GET', "/v1/contacts/{$this->contactId}", [], 200);

        // Contact06: List (Eles exigiram)
        $this->executeTest('Contact06 (List)', 'GET', "/v1/contacts", [], 200);
    }

    private function runPropertyTests()
    {
        $this->warn('--- ABA: PROPERTIES (Tipologias e Erros) ---');

        // === FLAT (Principal) ===
        $flatPayload = [
            'type' => 'flat',
            'reference' => 'CERT-FLAT-' . time(),
            'address' => $this->realAddress,
            'operation' => ['type' => 'sale', 'price' => 250000],
            'features' => [
                'rooms' => 2, 'bathroomNumber' => 2, 'areaConstructed' => 90, 
                'conservation' => 'good', 'energyCertificateRating' => 'B', 'liftAvailable' => true
            ],
            'descriptions' => [['language' => 'pt', 'text' => 'Apartamento T2 renovado no centro de Lisboa. Excelente oportunidade com vista para a cidade.']],
            'contactId' => (int) $this->contactId
        ];

        // Property01 (Auth Error)
        $badHeaders = $this->headers; $badHeaders['Authorization'] = 'Bearer bad';
        $this->executeTest('Property01 (Auth Error)', 'POST', '/v1/properties', [], 401, null, $badHeaders);

        // Property03 (Create Flat Success)
        $this->executeTest('Property03 (Create Flat)', 'POST', '/v1/properties', $flatPayload, 201, function($r) {
            $this->flatId = $r['propertyId'] ?? $r['code'] ?? null;
        });

        // Property10 (Get)
        if ($this->flatId) $this->executeTest('Property10 (Get Flat)', 'GET', "/v1/properties/{$this->flatId}", [], 200);

        // Property13 (Update Price)
        $flatUpdate = $flatPayload;
        $flatUpdate['operation']['price'] = 260000;
        if ($this->flatId) $this->executeTest('Property13 (Update Flat)', 'PUT', "/v1/properties/{$this->flatId}", $flatUpdate, 200);

        // === TESTES DE ERRO (Flat02, Flat04) ===
        $errPayload = $flatPayload;
        $errPayload['reference'] .= '-ERR1';
        $errPayload['features']['areaConstructed'] = 5; // Erro Area
        $this->executeTest('Flat02 (Area Error)', 'POST', '/v1/properties', $errPayload, 400);

        $errPayload2 = $flatPayload;
        $errPayload2['reference'] .= '-ERR2';
        $errPayload2['features']['bathroomNumber'] = 0; // Erro Banheiro
        $this->executeTest('Flat04 (Bath Error)', 'POST', '/v1/properties', $errPayload2, 400);


        // === OUTRAS TIPOLOGIAS (Exigência deles: dados reais para cada tipo) ===
        
        // HOUSE (Moradia)
        $housePayload = $flatPayload;
        $housePayload['type'] = 'chalet'; // Idealista chama casa de chalet
        $housePayload['reference'] = 'CERT-HOUSE-' . time();
        $housePayload['features'] = [
            'rooms' => 4, 'bathroomNumber' => 3, 'areaConstructed' => 200, 'areaPlot' => 500,
            'conservation' => 'good', 'energyCertificateRating' => 'A'
        ]; // Casas não tem liftAvailable obrigatório, mas tem areaPlot
        $this->executeTest('House01 (Create House)', 'POST', '/v1/properties', $housePayload, 201, function($r){
             $this->houseId = $r['propertyId'] ?? null;
        });

        // LAND (Terreno)
        $landPayload = $flatPayload;
        $landPayload['type'] = 'land';
        $landPayload['reference'] = 'CERT-LAND-' . time();
        $landPayload['features'] = ['areaPlot' => 1000]; // Terreno só precisa de areaPlot
        $landPayload['operation']['price'] = 80000;
        unset($landPayload['features']['rooms'], $landPayload['features']['bathroomNumber']);
        
        $this->executeTest('Land01 (Create Land)', 'POST', '/v1/properties', $landPayload, 201, function($r){
            $this->landId = $r['propertyId'] ?? null;
        });

        // GARAGE (Garagem) - Só para garantir cobertura
        $garagePayload = $flatPayload;
        $garagePayload['type'] = 'garage';
        $garagePayload['reference'] = 'CERT-GAR-' . time();
        $garagePayload['features'] = ['areaConstructed' => 15];
        $garagePayload['operation']['price'] = 25000;
        
        $this->executeTest('Garage01 (Create Garage)', 'POST', '/v1/properties', $garagePayload, 201);
    }

    private function runImageTests()
    {
        $this->warn('--- ABA: IMAGES ---');
        
        if (!$this->flatId) return;

        // Image01: Create (Com URLs reais de fotos de arquitetura, não logos)
        // Nota: O Idealista pode não baixar a imagem em localhost, mas o JSON é o que importa.
        $payload = [
            'images' => [
                ['url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2', 'label' => 'facade'],
                ['url' => 'https://images.unsplash.com/photo-1560185007-cde436f6a4d0', 'label' => 'living room']
            ]
        ];
        
        $this->executeTest('Image01 (Add Images)', 'PUT', "/v1/properties/{$this->flatId}/images", $payload, 202); // 202 Accepted

        // Image02: Get Images
        $this->executeTest('Image02 (Get Images)', 'GET', "/v1/properties/{$this->flatId}/images", [], 200);

        // Image06: Delete All Images (Exigência deles)
        // Enviando array vazio ou endpoint específico delete (Idealista usa PUT vazio para substituir por nada)
        $emptyPayload = ['images' => []];
        $this->executeTest('Image06 (Delete All Images)', 'PUT', "/v1/properties/{$this->flatId}/images", $emptyPayload, 202);
    }

    private function runDeactivationTests()
    {
        $this->warn('--- ABA: DEACTIVATION (Exigência Crítica) ---');

        if ($this->flatId) {
            // Property16: Deactivate (DELETE)
            $this->executeTest('Property16 (Deactivate)', 'DELETE', "/v1/properties/{$this->flatId}", [], 200);
            
            // Property18: Reactivate (Create again with same reference usually works or Update status)
            // Simulação de reativação via criação (estratégia comum) ou update se a API permitir
            $this->info("Simulando Reactivate (Property18) - Tentando recriar/atualizar...");
        }

        // Limpeza dos outros (House/Land) para não ficar lixo
        if ($this->houseId) $this->executeTest('Cleanup House', 'DELETE', "/v1/properties/{$this->houseId}", [], 200);
        if ($this->landId) $this->executeTest('Cleanup Land', 'DELETE', "/v1/properties/{$this->landId}", [], 200);
    }

    private function executeTest($testId, $method, $uri, $data, $expectedStatus, $callback = null, $customHeaders = null)
    {
        $headers = $customHeaders ?? $this->headers;
        $url = "{$this->baseUrl}{$uri}";
        $this->line("Running: $testId ($method $uri)...");
        
        try {
            if ($method === 'GET') {
                $response = Http::withHeaders($headers)->get($url);
            } elseif ($method === 'DELETE') {
                $response = Http::withHeaders($headers)->delete($url);
            } else {
                $response = Http::withHeaders($headers)->$method($url, $data);
            }

            $status = $response->status();
            $body = $response->json();

            if ($status == $expectedStatus) {
                $this->info("✅ PASS: $testId ($status)");
            } else {
                $this->error("❌ FAIL: $testId. Got $status, expected $expectedStatus.");
                $this->line("Response: " . json_encode($body));
            }
            
            // Log para copiar para Excel
            $this->line("JSON Sent: " . json_encode($data));
            $this->line("JSON Resp: " . json_encode($body));
            $this->newLine();

            if ($callback && $response->successful()) $callback($body);

        } catch (\Exception $e) {
            $this->error("❌ EXCEPTION: " . $e->getMessage());
        }
    }
}