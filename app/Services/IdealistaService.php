<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class IdealistaService
{
    protected $key;
    protected $secret;
    protected $feedKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->key = config('services.idealista.key');
        $this->secret = config('services.idealista.secret');
        $this->feedKey = config('services.idealista.feed_key');
        $this->baseUrl = config('services.idealista.base_url');
    }

    /**
     * Passo 1: Obter Token OAuth2 (Client Credentials)
     */
    protected function getToken()
    {
        // 1. Verifica se já existe um token válido em cache
        if (Cache::has('idealista_token')) {
            return Cache::get('idealista_token');
        }

        // 2. Se não existir, solicita um novo à API
        $credentials = base64_encode("{$this->key}:{$this->secret}");

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => "Basic {$credentials}",
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
            ])
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'client_credentials',
                'scope' => 'read' 
            ]);

        if ($response->failed()) {
            Log::error('Erro Auth Idealista: ' . $response->body());
            throw new Exception('Falha na autenticação com Idealista: ' . $response->status());
        }

        $data = $response->json();
        
        // 3. Calcula o tempo de expiração (TTL)
        // A API retorna 'expires_in' (ex: 300s). Subtraímos 60s para margem de segurança.
        $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] - 60 : 200;

        if ($expiresIn <= 0) {
            $expiresIn = 60; 
        }

        $accessToken = $data['access_token'];

        // 4. Guarda no Cache com o tempo dinâmico
        Cache::put('idealista_token', $accessToken, $expiresIn);

        return $accessToken;
    }

    /**
     * Helper para gerar os headers padrão (Step 2 & Identification)
     * AGORA É PUBLIC PARA O COMANDO PODER USAR NO DOWNLOAD DE IMAGENS
     */
    public function getHeaders()
    {
        $token = $this->getToken();
        return [
            'Authorization' => "Bearer {$token}",
            'feedKey' => $this->feedKey, // OBRIGATÓRIO conforme doc
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Puxar Imóveis (GET /v1/properties)
     */
    public function getProperties($page = 1, $size = 50)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties", [
                'page' => $page,
                'size' => $size
            ]);

        if ($response->failed()) {
            Log::error('Erro ao buscar imóveis: ' . $response->body());
            return [];
        }

        return $response->json(); 
    }

    /**
     * Buscar Imagens do Imóvel (GET /v1/properties/{id}/images)
     */
    public function getPropertyImages($propertyId)
    {
        $response = Http::withHeaders($this->getHeaders())
            ->get("{$this->baseUrl}/v1/properties/{$propertyId}/images");

        if ($response->failed()) {
            Log::warning("Falha ao buscar imagens para o imóvel {$propertyId}: " . $response->body());
            return [];
        }

        $json = $response->json();
        // Tenta retornar a chave 'images' ou o array direto
        return $json['images'] ?? $json ?? [];
    }

    /**
     * Mandar Imóvel (POST /v1/properties)
     */
    public function createProperty($localProperty)
    {
        $payload = $this->mapToIdealistaPayload($localProperty);

        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->baseUrl}/v1/properties", $payload);

        if ($response->failed()) {
            throw new Exception('Erro ao criar imóvel no Idealista: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Atualizar Imóvel (PUT /v1/properties/{id})
     */
    public function updateProperty($idealistaId, $localProperty)
    {
        $payload = $this->mapToIdealistaPayload($localProperty);

        $response = Http::withHeaders($this->getHeaders())
            ->put("{$this->baseUrl}/v1/properties/{$idealistaId}", $payload);

        if ($response->failed()) {
            throw new Exception('Erro ao atualizar imóvel no Idealista: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Mapeamento do seu Banco -> JSON do Idealista
     */
    protected function mapToIdealistaPayload($property)
    {
        return [
            'reference' => (string) $property->id,
            'propertyType' => $this->mapType($property->type),
            'operation' => $this->mapOperation($property->status),
            'price' => (float) $property->price,
            'address' => [
                'visibility' => 'hidden',
                'streetName' => $property->address ?? 'Rua Principal',
                'streetNumber' => '1',
                'postalCode' => $property->postal_code ?? '1000-001',
                'town' => $property->city ?? 'Lisboa',
                'country' => 'PT'
            ],
            'features' => [
                'rooms' => (int) $property->bedrooms,
                'baths' => (int) $property->bathrooms,
                'areaConstructed' => (int) $property->area_gross,
            ],
            'descriptions' => [
                ['language' => 'pt', 'text' => substr($property->description ?? '', 0, 3000)]
            ],
        ];
    }

    protected function mapType($type)
    {
        return match (strtolower($type)) {
            'apartamento' => 'flat',
            'moradia' => 'chalet',
            'terreno' => 'land',
            'comercial' => 'office',
            'garagem' => 'garage',
            'prédio' => 'building',
            default => 'flat',
        };
    }

    protected function mapOperation($status)
    {
        return match (strtolower($status)) {
            'arrendamento' => 'rent',
            default => 'sale',
        };
    }
}