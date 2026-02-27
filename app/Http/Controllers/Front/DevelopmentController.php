<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Development;
use Illuminate\Http\Request;

class DevelopmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Development::with('photos')->where('is_visible', true);

        if ($request->filled('location')) {
            $query->where('title', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $developments = $query->ordered()->paginate(12)->withQueryString();

        $statuses = Development::where('is_visible', true)
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->pluck('status');

        return view('front.developments.index', compact('developments', 'statuses'));
    }

    public function show($slug)
    {
        $development = Development::with(['photos' => function ($query) {
                $query->orderBy('order');
            }, 'fractions', 'neighborhoodPhotos' => function($query) {
                $query->orderBy('order');
            }])
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        $recommended = Development::with(['photos' => function($q) { $q->where('is_cover', true)->orWhere('order', 0); }])
            ->where('id', '!=', $development->id)
            ->where('is_visible', true)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('front.developments.show', compact('development', 'recommended'));
    }
}
