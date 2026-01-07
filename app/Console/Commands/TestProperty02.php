<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Services\IdealistaExportService;

class TestProperty02 extends Command
{
    protected $signature = 'idealista:test-p02';
    protected $description = 'Teste específico para Property02 - Invalid feedKey';

    public function handle(IdealistaExportService $service)
    {
        $this->info("🕵️‍♂️ Testando Property02: Invalid feedKey Header...");

        $baseUrl = config('services.idealista.base_url');
        
        // Payload válido (para garantir que o erro seja só de auth)
        $payload = [
            'type' => 'flat',
            'reference' => 'TEST-P02-' . time(),
            'address' => [
                'visibility' => 'hidden',
                'precision' => 'exact',
                'country' => 'Portugal',
                'streetName' => 'Avenida da Liberdade',
                'streetNumber' => '100',
                'postalCode' => '1250-145',
                'town' => 'Lisboa'
            ],
            'operation' => ['type' => 'sale', 'price' => 250000],
            'features' => ['rooms' => 2, 'bathroomNumber' => 2, 'areaConstructed' => 90, 'conservation' => 'good', 'energyCertificateRating' => 'B', 'liftAvailable' => true],
            'descriptions' => [['language' => 'pt', 'text' => 'Teste P02']],
            'contactId' => 33900812
        ];

        // CENÁRIO A: Header explícito 'feedKey'
        $this->line("\n--- Tentativa A: Header 'feedKey' inválido ---");
        $headersA = [
            'Content-Type' => 'application/json',
            'feedKey' => 'chave_invalida_teste' // O cabeçalho pedido
        ];
        // Nota: NÃO estamos enviando 'Authorization' aqui
        
        $responseA = Http::withHeaders($headersA)->post("$baseUrl/v1/properties", $payload);
        $this->info("Status: " . $responseA->status());
        $this->line("Body: " . $responseA->body());

        // CENÁRIO B: Header 'Authorization' com valor inválido (caso 'feedKey' seja apenas um nome fantasia para Auth)
        $this->line("\n--- Tentativa B: Header 'Authorization' inválido ---");
        $headersB = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer chave_invalida_teste'
        ];
        
        $responseB = Http::withHeaders($headersB)->post("$baseUrl/v1/properties", $payload);
        $this->info("Status: " . $responseB->status());
        $this->line("Body: " . $responseB->body());
    }
}