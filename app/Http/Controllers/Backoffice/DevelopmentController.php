<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Development;
use App\Models\DevelopmentFraction;
use App\Models\DevelopmentPhoto;
use App\Models\DevelopmentNeighborhoodPhoto;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DevelopmentController extends Controller
{
    /**
     * INDEX ADMIN
     */
    public function index(Request $request)
    {
        if (!$request->has('visibility')) {
            $request->merge(['visibility' => 'active']);
        }

        $query = Development::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('visibility') && $request->visibility !== 'all') {
            $value = ($request->visibility === 'active') ? 1 : 0;
            $query->where('is_visible', $value);
        }

        $developments = $query->ordered()->paginate(10)->withQueryString();

        return view('admin.developments.index', compact('developments'));
    }

    public function create()
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.developments.create', compact('consultants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'consultant_id' => 'nullable|exists:consultants,id',
            'status' => 'nullable|string',
            'typologies' => 'nullable|string',
            'areas' => 'nullable|string',
            'built_year' => 'nullable|string',
            'energy_rating' => 'nullable|string',
            'description' => 'nullable|string',
            'neighborhood_description' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            
            // Files
            'brochure' => 'nullable|file|mimes:pdf,pdf,doc,docx|max:20480',
            'finishes_map' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'development_sheet' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            
            // Gallery
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',

            // Neighborhood Gallery
            'neighborhood_gallery' => 'nullable|array',
            'neighborhood_gallery.*' => 'image|max:20480',
            
            // Fractions Array
            'fractions' => 'nullable|array',
            'fractions.*.floor_plan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        
        if (!isset($data['order']) || $data['order'] === null) {
            $maxOrder = Development::max('order');
            $data['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }

        // Upload files
        if ($request->hasFile('brochure')) {
            $data['brochure_path'] = $request->file('brochure')->store('developments/docs', 'public');
        }
        if ($request->hasFile('finishes_map')) {
            $data['finishes_map_path'] = $request->file('finishes_map')->store('developments/docs', 'public');
        }
        if ($request->hasFile('development_sheet')) {
            $data['development_sheet_path'] = $request->file('development_sheet')->store('developments/docs', 'public');
        }

        // Create development
        $development = Development::create($data);

        // Upload Gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                if ($image->isValid()) {
                    $path = $image->store('developments/gallery', 'public');
                    DevelopmentPhoto::create([
                        'development_id' => $development->id,
                        'path' => $path,
                        'order' => $index,
                        'is_cover' => $index === 0 // First is cover
                    ]);
                }
            }
        }

        // Upload Neighborhood Gallery
        if ($request->hasFile('neighborhood_gallery')) {
            foreach ($request->file('neighborhood_gallery') as $index => $image) {
                if ($image->isValid()) {
                    $path = $image->store('developments/neighborhood', 'public');
                    DevelopmentNeighborhoodPhoto::create([
                        'development_id' => $development->id,
                        'path' => $path,
                        'order' => $index
                    ]);
                }
            }
        }

        // Handle Fractions (assuming an array coming from JS)
        if ($request->has('fractions') && is_array($request->fractions)) {
            foreach ($request->fractions as $frac) {
                if (!empty($frac['ref']) || !empty($frac['typology'])) { // basic validation
                    $fracData = [
                        'development_id' => $development->id,
                        'ref' => $frac['ref'] ?? null,
                        'block' => $frac['block'] ?? null,
                        'floor' => $frac['floor'] ?? null,
                        'typology' => $frac['typology'] ?? null,
                        'abp' => $frac['abp'] ?? null,
                        'balcony_area' => $frac['balcony_area'] ?? null,
                        'parking_spaces' => $frac['parking_spaces'] ?? null,
                        'price' => $frac['price'] ?? null,
                        'remax_id' => $frac['remax_id'] ?? null,
                        'status' => $frac['status'] ?? 'Disponível',
                    ];
                    
                    if (isset($frac['floor_plan']) && $frac['floor_plan'] instanceof \Illuminate\Http\UploadedFile && $frac['floor_plan']->isValid()) {
                        $fracData['floor_plan_path'] = $frac['floor_plan']->store('developments/fractions', 'public');
                    }
                    
                    DevelopmentFraction::create($fracData);
                }
            }
        }

        return redirect()->route('admin.developments.index')->with('success', 'Empreendimento cadastrado com sucesso!');
    }

    public function edit(Development $development)
    {
        $development->load(['photos', 'neighborhoodPhotos', 'fractions', 'consultant']);
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.developments.edit', compact('development', 'consultants'));
    }

    public function update(Request $request, Development $development)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'consultant_id' => 'nullable|exists:consultants,id',
            'status' => 'nullable|string',
            'typologies' => 'nullable|string',
            'areas' => 'nullable|string',
            'built_year' => 'nullable|string',
            'energy_rating' => 'nullable|string',
            'description' => 'nullable|string',
            'neighborhood_description' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            
            // Files 
            'brochure' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'finishes_map' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            'development_sheet' => 'nullable|file|mimes:pdf,doc,docx|max:20480',
            
            // Photos
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'images_order' => 'nullable|string', 
            'cover_image_id' => 'nullable|exists:development_photos,id',

            // Neighborhood Photos
            'neighborhood_gallery' => 'nullable|array',
            'neighborhood_gallery.*' => 'image|max:20480',
            'neighborhood_images_order' => 'nullable|string', 
            
            // Fractions
            'fractions' => 'nullable|array',
            'fractions.*.floor_plan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($development->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        // Upload files & Delete old ones if replacing
        if ($request->hasFile('brochure')) {
            if ($development->brochure_path) Storage::disk('public')->delete($development->brochure_path);
            $data['brochure_path'] = $request->file('brochure')->store('developments/docs', 'public');
        }
        if ($request->hasFile('finishes_map')) {
            if ($development->finishes_map_path) Storage::disk('public')->delete($development->finishes_map_path);
            $data['finishes_map_path'] = $request->file('finishes_map')->store('developments/docs', 'public');
        }
        if ($request->hasFile('development_sheet')) {
            if ($development->development_sheet_path) Storage::disk('public')->delete($development->development_sheet_path);
            $data['development_sheet_path'] = $request->file('development_sheet')->store('developments/docs', 'public');
        }

        $development->update($data);

        // --- Gallery Handling ---
        $existingOrderIds = $request->filled('images_order') 
            ? explode(',', $request->images_order) 
            : [];

        // Delete photos removed in UI
        $imagesToDelete = $development->photos()->whereNotIn('id', $existingOrderIds)->get();
        foreach ($imagesToDelete as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        // Reorder & set cover
        foreach ($existingOrderIds as $index => $id) {
            $isCover = ($request->cover_image_id == $id);
            DevelopmentPhoto::where('id', $id)->update([
                'order' => $index,
                'is_cover' => $isCover
            ]);
        }

        // Append new photos
        if ($request->hasFile('gallery')) {
            $lastOrder = DevelopmentPhoto::where('development_id', $development->id)->max('order') ?? -1;

            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $lastOrder++;
                    $path = $image->store('developments/gallery', 'public');
                    DevelopmentPhoto::create([
                        'development_id' => $development->id,
                        'path' => $path,
                        'order' => $lastOrder,
                        'is_cover' => ($lastOrder === 0 && !DevelopmentPhoto::where('development_id', $development->id)->where('is_cover', true)->exists())
                    ]);
                }
            }
        }

        // --- Fractions Handling ---
        // Simple strategy: delete missing fractions by id loop, update existing, create new
        if ($request->has('fractions')) {
            $keptFractionIds = collect($request->fractions)->filter(fn($f) => isset($f['id']))->pluck('id')->toArray();
            
            // Delete missing fraction floor plans from storage
            $fractionsToDelete = $development->fractions()->whereNotIn('id', $keptFractionIds)->get();
            foreach ($fractionsToDelete as $oldFrac) {
                if ($oldFrac->floor_plan_path) Storage::disk('public')->delete($oldFrac->floor_plan_path);
            }
            $development->fractions()->whereNotIn('id', $keptFractionIds)->delete();
            
            foreach ($request->fractions as $frac) {
                if (!empty($frac['ref']) || !empty($frac['typology'])) {
                    $fracData = [
                        'development_id' => $development->id,
                        'ref' => $frac['ref'] ?? null,
                        'block' => $frac['block'] ?? null,
                        'floor' => $frac['floor'] ?? null,
                        'typology' => $frac['typology'] ?? null,
                        'abp' => $frac['abp'] ?? null,
                        'balcony_area' => $frac['balcony_area'] ?? null,
                        'parking_spaces' => $frac['parking_spaces'] ?? null,
                        'price' => $frac['price'] ?? null,
                        'remax_id' => $frac['remax_id'] ?? null,
                        'status' => $frac['status'] ?? 'Disponível',
                    ];

                    if (isset($frac['id']) && $frac['id']) {
                        DevelopmentFraction::where('id', $frac['id'])->update($fracData);
                    } else {
                        DevelopmentFraction::create($fracData);
                    }
                }
            }
        }

        // --- Neighborhood Gallery Handling ---
        $existingNeighborhoodOrderIds = $request->filled('neighborhood_images_order') 
            ? explode(',', $request->neighborhood_images_order) 
            : [];

        // Delete photos removed in UI
        $neighborhoodImagesToDelete = $development->neighborhoodPhotos()->whereNotIn('id', $existingNeighborhoodOrderIds)->get();
        foreach ($neighborhoodImagesToDelete as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        // Reorder
        foreach ($existingNeighborhoodOrderIds as $index => $id) {
            DevelopmentNeighborhoodPhoto::where('id', $id)->update(['order' => $index]);
        }

        // Append new neighborhood photos
        if ($request->hasFile('neighborhood_gallery')) {
            $lastNeighborhoodOrder = DevelopmentNeighborhoodPhoto::where('development_id', $development->id)->max('order') ?? -1;

            foreach ($request->file('neighborhood_gallery') as $image) {
                if ($image->isValid()) {
                    $lastNeighborhoodOrder++;
                    $path = $image->store('developments/neighborhood', 'public');
                    DevelopmentNeighborhoodPhoto::create([
                        'development_id' => $development->id,
                        'path' => $path,
                        'order' => $lastNeighborhoodOrder
                    ]);
                }
            }
        }

        return redirect()->route('admin.developments.index')->with('success', 'Empreendimento atualizado com sucesso!');
    }

    public function destroy(Development $development)
    {
        if ($development->brochure_path) Storage::disk('public')->delete($development->brochure_path);
        if ($development->finishes_map_path) Storage::disk('public')->delete($development->finishes_map_path);
        if ($development->development_sheet_path) Storage::disk('public')->delete($development->development_sheet_path);
        
        foreach ($development->photos as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        foreach ($development->neighborhoodPhotos as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        foreach ($development->fractions as $frac) {
            if ($frac->floor_plan_path) Storage::disk('public')->delete($frac->floor_plan_path);
        }
        
        // Fractions and photos will be deleted automatically via cascade constraint on DB 
        $development->delete();
        return back()->with('success', 'Empreendimento removido permanentemente.');
    }

    public function toggleVisibility(Development $development)
    {
        $development->update([
            'is_visible' => !$development->is_visible
        ]);

        $status = $development->is_visible ? 'Ativo' : 'Inativo';
        return back()->with('success_status', $status);
    }

    public function reorder(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $index => $id) {
            Development::where('id', $id)->update(['order' => $index]);
        }
        return response()->json(['status' => 'success']);
    }

    public function moveToTop(Development $development)
    {
        $minOrder = Development::min('order') ?? 0;
        $development->update(['order' => $minOrder - 1]);
        return back()->with('success', 'Empreendimento movido para o topo com sucesso!');
    }
}
