<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

// O NOME DA CLASSE ABAIXO DEVE SER IGUAL AO NOME DO ARQUIVO
class ConsultantController extends Controller
{
    private function getConsultantByDomain($domain)
    {
        $host = strtolower(str_replace(['http://', 'https://', 'www.'], '', $domain));
        $slug = explode('.', $host)[0];

        $consultant = Consultant::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $consultant) {
            $consultant = Consultant::where(function($query) use ($host) {
                    $query->where('custom_domain', $host)
                          ->orWhere('domain', $host);
                })
                ->where('is_active', true)
                ->firstOrFail();
        }

        return $consultant;
    }

    public function index($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    public function showProperty($domain, $slug)
    {
        $consultant = $this->getConsultantByDomain($domain);
        $property = Property::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('properties.show', compact('property', 'consultant'));
    }

    public function showCredit($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.credit', compact('consultant'));
    }

    public function showGains($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.gains', compact('consultant'));
    }

    public function showImt($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.imt', compact('consultant'));
    }

    public function preview(Consultant $consultant)
    {
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }
}