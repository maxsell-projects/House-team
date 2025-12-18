<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class AbstractIdealistaService
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
     * Obtém o token solicitando o escopo específico (read ou write)
     */
    protected function getToken($scope = 'read')
    {
        // Cache separado por escopo para não misturar permissões
        $cacheKey = "idealista_token_{$scope}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $credentials = base64_encode("{$this->key}:{$this->secret}");

        try {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => "Basic {$credentials}",
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                ])
                ->post("{$this->baseUrl}/oauth/token", [
                    'grant_type' => 'client_credentials',
                    'scope' => $scope // Passamos 'read' ou 'write' estritamente
                ]);

            if ($response->failed()) {
                Log::error("Erro Auth Idealista ($scope): " . $response->body());
                throw new Exception("Falha na autenticação ($scope): " . $response->status());
            }

            $data = $response->json();
            $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] - 60 : 200;
            if ($expiresIn <= 0) $expiresIn = 60;

            Cache::put($cacheKey, $data['access_token'], $expiresIn);

            return $data['access_token'];
        } catch (\Exception $e) {
            Log::critical("Exceção Auth Idealista ($scope): " . $e->getMessage());
            throw $e;
        }
    }

    public function getHeaders($scope = 'read')
    {
        $token = $this->getToken($scope);
        
        return [
            'Authorization' => "Bearer {$token}",
            'feedKey' => $this->feedKey, // Obrigatório em todos os endpoints segundo o YAML
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}