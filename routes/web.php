<?php

use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PropertyController;

Route::get('/', function () {
    $properties = Property::where('is_visible', true)->latest()->take(3)->get(); 
    return view('home', compact('properties'));
})->name('home');

Route::get('/sobre', function () {
    return view('about');
})->name('about');

Route::get('/imoveis', [PropertyController::class, 'publicIndex'])->name('portfolio');
Route::get('/imoveis/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/ferramentas/simulador-credito', function () {
    return view('tools.credit');
})->name('tools.credit');

Route::get('/ferramentas/mais-valias', function () {
    return view('tools.gains');
})->name('tools.gains');

Route::get('/ferramentas/imt', function () {
    return view('tools.imt');
})->name('tools.imt');

Route::get('/blog', function () {
    return view('blog.index');
})->name('blog');

Route::get('/blog/novo-perfil-investidor-luxo', function () {
    return view('blog.show');
})->name('blog.show');

Route::get('/blog/inteligencia-mercado-redefine-investimento', function () {
    return view('blog.show-intelligence');
})->name('blog.show-intelligence');

Route::get('/blog/lisboa-cascais-algarve-eixos-valor', function () {
    return view('blog.show-locations');
})->name('blog.show-locations');

// --- ROTA DE CONTATO ATUALIZADA ---
Route::get('/contato', function () {
    return view('contact');
})->name('contact');
// ----------------------------------

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::resource('properties', PropertyController::class)->names('admin.properties');
    });
});