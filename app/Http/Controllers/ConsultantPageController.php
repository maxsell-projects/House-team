<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Http\Request;

class ConsultantPageController extends Controller
{
    /**
     * Carrega a Homepage baseada no DOMÍNIO (ex: casaacasa.pt)
     */
    public function index($domain)
    {
        $consultant = $this->resolveConsultant($domain);

        if (!$consultant) {
            abort(404); 
        }

        // 3. Carrega os imóveis (Top 6 ordenados)
        $properties = $this->getAvailableProperties();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * [NOVO] Carrega o Detalhe do Imóvel mantendo o domínio da Consultora
     */
    public function showProperty($domain, Property $property)
    {
        // 1. Identifica a consultora dona do domínio
        $consultant = $this->resolveConsultant($domain);

        if (!$consultant) {
            abort(404);
        }

        // 2. Garante que o imóvel está visível
        if (!$property->is_visible) {
            abort(404);
        }

        // 3. Retorna a view padrão de imóvel, mas injetando a $consultant.
        // A presença dessa variável é o que ativa o "Modo Personalizado" no layout (cores/logo).
        return view('properties.show', compact('property', 'consultant'));
    }

    /**
     * Preview Interno (Modal)
     */
    public function preview(Consultant $consultant)
    {
        if (!$consultant->has_lp || !$consultant->is_active) {
            abort(404);
        }
        $properties = $this->getAvailableProperties();
        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Helper privado para resolver a Consultora pelo Domínio ou Slug
     * (Evita repetição de código)
     */
    private function resolveConsultant($domain)
    {
        // 1. Limpeza do domínio (remove www. se existir)
        $domain = preg_replace('/^www\./', '', $domain);

        // 2. Busca pelo domínio exato
        $consultant = Consultant::where('domain', $domain)
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();

        // 3. Fallback: Tenta pelo slug
        if (!$consultant) {
             $consultant = Consultant::where('lp_slug', $domain)
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();
        }

        return $consultant;
    }

    /**
     * Helper privado para pegar imóveis da home
     */
    private function getAvailableProperties()
    {
        return Property::where('is_visible', true)
            ->ordered()
            ->take(6) 
            ->get();
    }
}