<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IdealistaImportService extends AbstractIdealistaService
{
    /**
     * Busca lista de imóveis.
     * Lógica idêntica ao código original: Apenas page e size, sem filtrar status.
     */
    public function getProperties($page = 1, $size = 50)
    {
        // No código antigo o scope era fixo em 'read'
        $headers = $this->getHeaders('read');

        // Requisição simples, sem inventar filtros extras
        $response = Http::withHeaders($headers)
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
     * Busca imagens do imóvel.
     * Idêntico ao original.
     */
    public function getPropertyImages($propertyId)
    {
        $headers = $this->getHeaders('read');
        
        $response = Http::withHeaders($headers)
            ->get("{$this->baseUrl}/v1/properties/{$propertyId}/images");

        if ($response->failed()) {
            Log::warning("Falha ao buscar imagens para o imóvel {$propertyId}: " . $response->body());
            return [];
        }

        $json = $response->json();
        return $json['images'] ?? $json ?? [];
    }
}