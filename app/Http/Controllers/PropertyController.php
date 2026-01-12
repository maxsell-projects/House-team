<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index()
    {
        // Alterado para respeitar a ordenação manual no painel
        $properties = Property::ordered()->paginate(10);
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Recebe a nova ordem via AJAX e salva no banco (Drag & Drop na página atual)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:properties,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->ids as $index => $id) {
                    // O índice do array vira a ordem (1, 2, 3...)
                    Property::where('id', $id)->update(['order' => $index + 1]);
                }
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * NOVO: Move o imóvel para a primeira posição absoluta da lista (todas as páginas).
     * Resolve o problema da paginação no Drag & Drop.
     */
    public function moveToTop(Property $property)
    {
        // Encontra o menor valor de ordem atual na tabela inteira
        // Se a tabela estiver vazia (null), assume 0
        $minOrder = Property::min('order') ?? 0;

        // Define a ordem deste imóvel para ser menor que o mínimo atual
        // Ex: Se o mínimo é 1, este vira 0. Se é -10, vira -11.
        $property->update([
            'order' => $minOrder - 1
        ]);

        return back()->with('success', 'Imóvel movido para o topo com sucesso!');
    }

    public function create()
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.properties.create', compact('consultants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'crm_code' => 'nullable|string|max:50',
            'consultant_id' => 'nullable|exists:consultants,id',
            'price' => 'nullable|numeric',
            'type' => 'required|string',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'floor' => 'nullable|string',
            'orientation' => 'nullable|string',
            'area_gross' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garages' => 'nullable|integer',
            'energy_rating' => 'nullable|string',
            'condition' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'has_pool' => 'nullable',
            'has_garden' => 'nullable',
            'has_lift' => 'nullable',
            'has_terrace' => 'nullable',
            'has_air_conditioning' => 'nullable',
            'is_furnished' => 'nullable',
            'is_kitchen_equipped' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        
        $features = [
            'has_pool', 'has_garden', 'has_lift', 'has_terrace', 'has_air_conditioning', 
            'is_furnished', 'is_kitchen_equipped'
        ];
        
        foreach ($features as $feature) {
            $data[$feature] = $request->has($feature);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('properties', 'public');
        }

        $property = Property::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path
                    ]);
                }
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Imóvel cadastrado com sucesso!');
    }

    public function edit(Property $property)
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.properties.edit', compact('property', 'consultants'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'crm_code' => 'nullable|string|max:50',
            'consultant_id' => 'nullable|exists:consultants,id',
            'price' => 'nullable|numeric',
            'type' => 'required|string',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'floor' => 'nullable|string',
            'orientation' => 'nullable|string',
            'area_gross' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garages' => 'nullable|integer',
            'energy_rating' => 'nullable|string',
            'condition' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'has_pool' => 'nullable',
            'has_garden' => 'nullable',
            'has_lift' => 'nullable',
            'has_terrace' => 'nullable',
            'has_air_conditioning' => 'nullable',
            'is_furnished' => 'nullable',
            'is_kitchen_equipped' => 'nullable',
        ]);

        if ($property->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        $features = [
            'has_pool', 'has_garden', 'has_lift', 'has_terrace', 'has_air_conditioning', 
            'is_furnished', 'is_kitchen_equipped'
        ];
        
        foreach ($features as $feature) {
            $data[$feature] = $request->has($feature);
        }

        if ($request->hasFile('cover_image')) {
            if ($property->cover_image) {
                Storage::disk('public')->delete($property->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('properties', 'public');
        }

        $property->update($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path
                    ]);
                }
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Imóvel atualizado com sucesso!');
    }

    public function destroy(Property $property)
    {
        if ($property->cover_image) {
            Storage::disk('public')->delete($property->cover_image);
        }
        
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        $property->delete();
        return back()->with('success', 'Imóvel removido.');
    }

    public function publicIndex(Request $request)
    {
        $query = Property::with('images')->where('is_visible', true);

        if ($request->filled('location')) {
            $search = $request->location;
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('bedrooms')) {
            if ($request->bedrooms == '4+') {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', $request->bedrooms);
            }
        }

        // Alterado de latest() para ordered() para o site refletir a ordem manual
        $properties = $query->ordered()->paginate(9)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    public function show(Request $request, Property $property)
    {
        // Garante que o imóvel está visível
        if (!$property->is_visible) {
            abort(404);
        }

        // Carrega imagens ordenadas
        $property->load(['images' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'consultant']);

        // LÓGICA DO MODO CONSULTORA
        $consultant = null;

        // 1. Se a URL tiver ?cid=X (Preview ou link compartilhado)
        if ($request->has('cid')) {
            $consultant = Consultant::find($request->cid);
        }
        // 2. Se estiver acessando via domínio próprio da consultora
        elseif ($request->route('domain')) {
             $domain = preg_replace('/^www\./', '', $request->route('domain'));
             $consultant = Consultant::where('domain', $domain)
                ->orWhere('lp_slug', $domain)
                ->first();
        }

        return view('properties.show', compact('property', 'consultant'));
    }
}