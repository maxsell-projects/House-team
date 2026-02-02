<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HighLevelService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://rest.gohighlevel.com/v1';
        $this->apiKey = config('services.ghl.api_key');
    }

    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Cria ou Atualiza um contato de forma segura (sem apagar dados).
     */
    public function createContact(array $data)
    {
        // 1. Tenta encontrar o contato existente (Estratégia Dupla)
        $existingContact = null;

        // Check A: Por Email
        if (!empty($data['email'])) {
            $existingContact = $this->lookupContact($data['email']);
        }

        // Check B: Por Telefone (Se não achou por email e tem telefone)
        // Isso impede a criação duplicada se o cliente mudou o email mas manteve o nº
        if (!$existingContact && !empty($data['phone'])) {
            $existingContact = $this->lookupContactByPhone($data['phone']);
        }

        // 2. Se existe, ATUALIZA (Merge) para não perder histórico
        if ($existingContact) {
            Log::info("GHL: Contato existente encontrado (ID: {$existingContact['id']}). Iniciando atualização segura.");
            return $this->updateContact($existingContact, $data);
        }

        // 3. Se não existe, CRIA um novo
        return $this->createNewContact($data);
    }

    private function createNewContact(array $data)
    {
        $names = $this->splitName($data['name'] ?? '');

        $payload = [
            'firstName' => $names['firstName'],
            'lastName'  => $names['lastName'],
            'email'     => $data['email'] ?? null,
            'phone'     => $data['phone'] ?? null,
            'tags'      => $data['tags'] ?? [],
            'source'    => $data['source'] ?? 'Site House Team',
        ];

        // Remove campos vazios para evitar erro na API
        $payload = array_filter($payload, fn($value) => !empty($value));

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/", $payload);

            if ($response->successful()) {
                $contact = $response->json('contact');
                Log::info('GHL: Novo contato criado com sucesso. ID: ' . ($contact['id'] ?? 'N/A'));
                return $contact;
            }

            Log::warning('GHL: Falha na criação direta. Body: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('GHL: Exceção CreateContact', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Atualiza o contato mesclando dados (não destrutivo).
     */
    private function updateContact(array $existing, array $newData)
    {
        $contactId = $existing['id'];
        
        // Mescla Tags: Mantém as antigas e adiciona as novas para não perder histórico
        $existingTags = $existing['tags'] ?? [];
        $newTags = $newData['tags'] ?? [];
        $mergedTags = array_unique(array_merge($existingTags, $newTags));

        // Prepara payload de atualização
        $payload = [
            'tags' => array_values($mergedTags), // Garante array indexado
            'source' => $newData['source'] ?? ($existing['source'] ?? 'Site House Team'),
        ];

        // Só atualiza email se o existente for vazio (evita sobrescrever o principal)
        if (empty($existing['email']) && !empty($newData['email'])) {
            $payload['email'] = $newData['email'];
        }

        // Só atualiza telefone se o existente for vazio
        if (empty($existing['phone']) && !empty($newData['phone'])) {
            $payload['phone'] = $newData['phone'];
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->put("{$this->baseUrl}/contacts/{$contactId}", $payload);

            if ($response->successful()) {
                Log::info("GHL: Contato {$contactId} atualizado com sucesso (Tags mescladas).");
                return $response->json('contact');
            }
            
            Log::error("GHL: Erro ao atualizar contato {$contactId}: " . $response->body());
            return $existing; // Retorna o original se falhar o update

        } catch (\Exception $e) {
            Log::error('GHL: Exceção UpdateContact', ['msg' => $e->getMessage()]);
            return $existing;
        }
    }

    public function lookupContact($email)
    {
        if (empty($email)) return null;
        return $this->genericLookup(['email' => $email]);
    }

    public function lookupContactByPhone($phone)
    {
        if (empty($phone)) return null;
        return $this->genericLookup(['phone' => $phone]);
    }

    private function genericLookup($queryParams)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/contacts/lookup", $queryParams);
            
            if ($response->successful()) {
                $contacts = $response->json('contacts');
                return $contacts[0] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('GHL: Erro Lookup', ['params' => $queryParams, 'msg' => $e->getMessage()]);
        }
        return null;
    }

    public function assignUser($contactId, $userId)
    {
        if (!$contactId || !$userId) return;

        try {
            Http::withHeaders($this->getHeaders())
                ->put("{$this->baseUrl}/contacts/{$contactId}", [
                    'assignedTo' => $userId
                ]);
            Log::info("GHL: Contacto {$contactId} atribuído ao user {$userId}");
        } catch (\Exception $e) {
            Log::error('GHL: Erro assignUser', ['msg' => $e->getMessage()]);
        }
    }

    public function addNote(string $contactId, string $noteContent)
    {
        if (empty($noteContent)) return;

        try {
            Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/contacts/{$contactId}/notes", [
                    'body' => $noteContent
                ]);
        } catch (\Exception $e) {
            Log::error('GHL: Erro addNote', ['msg' => $e->getMessage()]);
        }
    }

    /**
     * Cria a Oportunidade no CRM.
     * CORREÇÃO: Mapeia a descrição do formulário para o campo 'notes' da Oportunidade.
     */
    public function createOpportunity(string $contactId, array $data, string $type = 'lead')
    {
        $configMap = [
            'buyer'       => ['pipe' => 'buyers',      'stage' => 'buyers_new',      'suffix' => ' - Comprador'],
            'seller'      => ['pipe' => 'sellers',     'stage' => 'sellers_new',     'suffix' => ' - Venda de Imóvel'],
            'valuation'   => ['pipe' => 'sellers',     'stage' => 'sellers_new',     'suffix' => ' - Pedido de Avaliação'],
            'recruitment' => ['pipe' => 'recruitment', 'stage' => 'recruitment_new', 'suffix' => ' - Candidatura Espontânea'],
            'credit'      => ['pipe' => 'credit',      'stage' => 'credit_new',      'suffix' => ' - Crédito Habitação'],
            'lead'        => ['pipe' => 'buyers',      'stage' => 'buyers_new',      'suffix' => ' - Lead Site'],
        ];

        $conf = $configMap[$type] ?? $configMap['lead'];

        $pipelineId = config("services.ghl.pipelines.{$conf['pipe']}");
        $stageId    = config("services.ghl.stages.{$conf['stage']}");

        if (!$pipelineId || !$stageId) {
            Log::critical("GHL: Configuração CRÍTICA ausente para: {$type}. Verifique config/services.php");
            return false;
        }

        $payload = [
            'pipelineId' => $pipelineId,
            'stageId'    => $stageId,
            'contactId'  => $contactId,
            'title'      => ($data['name'] ?? 'Lead') . $conf['suffix'],
            'status'     => 'open',
            'source'     => $data['source'] ?? 'Site House Team',
            // AQUI ESTÁ A CORREÇÃO: Injeta a descrição no campo Notes da Oportunidade
            'notes'      => $data['description'] ?? null,
        ];

        if (!empty($data['property_price'])) {
            $price = $data['property_price'];
            if (is_string($price)) {
                $price = str_replace(['.', ' ', '€'], '', $price);
                $price = str_replace(',', '.', $price);
            }
            $payload['monetaryValue'] = (float) $price;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/pipelines/{$pipelineId}/opportunities", $payload);

            if ($response->successful()) {
                Log::info("GHL: Oportunidade criada no pipeline [{$conf['pipe']}] com notas de negócio.");
                return true;
            }

            Log::error('GHL: Falha API Opportunity', ['body' => $response->body()]);
            return false;

        } catch (\Exception $e) {
            Log::error('GHL: Exceção CreateOpportunity', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function getPipelines()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get("{$this->baseUrl}/pipelines/");
            return $response->json('pipelines');
        } catch (\Exception $e) { return []; }
    }

    private function splitName($fullname)
    {
        $parts = explode(' ', trim($fullname), 2);
        return [
            'firstName' => $parts[0] ?? '',
            'lastName'  => $parts[1] ?? ''
        ];
    }
}