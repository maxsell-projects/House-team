<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Http\Request;

class ConsultantPageController extends Controller
{
    /**
     * Carrega a Homepage do Consultor baseada no domínio externo.
     * Ex: ana.127.0.0.1.nip.io
     */
    public function index($domain)
    {
        // Limpeza: remove porta se existir (ex: :8000) para bater com o banco
        $domain = preg_replace('/:\d+$/', '', $domain);

        // 1. Tenta encontrar o consultor dono deste domínio
        $consultant = Consultant::where('domain', $domain)
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();

        // 2. PROTEÇÃO: Se não achar, 404.
        if (!$consultant) {
            abort(404); 
        }

        // 3. ALTERADO: Carrega TODOS os imóveis visíveis da imobiliária
        // (Antes carregava apenas deste consultor)
        $properties = $this->getAllProperties();

        // 4. Retorna a view da LP
        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * NOVO: Carrega a Homepage para o Modal (Preview Interno).
     * Usa o ID do consultor, não o domínio.
     */
    public function preview(Consultant $consultant)
    {
        // Validação de Segurança: Só mostra se tiver LP ativa
        if (!$consultant->has_lp || !$consultant->is_active) {
            abort(404);
        }

        // ALTERADO: Carrega TODOS os imóveis (reutilizando a lógica global)
        $properties = $this->getAllProperties();

        // Retorna a MESMA view, garantindo que o modal seja idêntico ao site real
        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Mostra um imóvel específico DENTRO do site do consultor.
     */
    public function showProperty($domain, $slug)
    {
        $domain = preg_replace('/:\d+$/', '', $domain);

        $consultant = Consultant::where('domain', $domain)
                        ->where('has_lp', true)
                        ->firstOrFail();
        
        // ALTERADO: Removemos a restrição de 'consultant_id'
        // Agora o consultor pode exibir qualquer imóvel ativo da empresa
        $property = Property::where('slug', $slug)
                        // ->where('consultant_id', $consultant->id) // <--- REMOVIDO PARA MOSTRAR GERAL
                        ->where('is_visible', true)
                        ->firstOrFail();

        return view('consultants.property-show', compact('consultant', 'property'));
    }

    /**
     * Método auxiliar privado para buscar imóveis globais
     * Renomeado para ficar claro que traz tudo.
     */
    private function getAllProperties()
    {
        // Retorna todos os imóveis visíveis ordenados (scopeOrdered do Model)
        return Property::where('is_visible', true)
            ->ordered()
            ->get();
    }
}