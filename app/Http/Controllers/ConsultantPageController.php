<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Http\Request;

class ConsultantPageController extends Controller
{
    /**
     * Carrega a LP via URL: houseteam.pt/{slug}
     */
    public function index($slug)
    {
        // Procura pelo lp_slug (ex: 'margarida') OU pelo domain (caso uses esse campo provisoriamente)
        $consultant = Consultant::where(function($query) use ($slug) {
                            $query->where('lp_slug', $slug)
                                  ->orWhere('domain', $slug);
                        })
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();

        if (!$consultant) {
            abort(404); 
        }

        $properties = $this->getAllProperties();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Preview Interno (Modal). Mantém-se igual.
     */
    public function preview(Consultant $consultant)
    {
        if (!$consultant->has_lp || !$consultant->is_active) {
            abort(404);
        }
        $properties = $this->getAllProperties();
        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Detalhe do Imóvel dentro da LP do Consultor
     * URL: houseteam.pt/{slug}/imovel/{imovel-slug}
     */
    public function showProperty($slug, $propertySlug)
    {
        // Valida se o consultor existe
        $consultant = Consultant::where(function($query) use ($slug) {
                            $query->where('lp_slug', $slug)
                                  ->orWhere('domain', $slug);
                        })
                        ->where('has_lp', true)
                        ->firstOrFail();
        
        // Busca o imóvel
        $property = Property::where('slug', $propertySlug)
                        ->where('is_visible', true)
                        ->firstOrFail();

        return view('consultants.property-show', compact('consultant', 'property'));
    }

    private function getAllProperties()
    {
        return Property::where('is_visible', true)
            ->ordered()
            ->get();
    }
}