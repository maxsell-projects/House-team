<?php

use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ToolsController; // <--- OBRIGATÓRIO: Importar este controller

// --- HOME & PÁGINAS GERAIS ---
Route::get('/', function () {
    $properties = Property::where('is_visible', true)->latest()->take(3)->get(); 
    return view('home', compact('properties'));
})->name('home');

Route::get('/sobre', function () {
    return view('about');
})->name('about');

// --- IMÓVEIS ---
Route::get('/imoveis', [PropertyController::class, 'publicIndex'])->name('portfolio');
Route::get('/imoveis/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// --- FERRAMENTAS ---

// Simulador de Crédito (Cálculo feito no browser, só precisa de ver a página)
Route::get('/ferramentas/simulador-credito', function () {
    return view('tools.credit');
})->name('tools.credit');

// Simulador de IMT (Cálculo feito no browser, só precisa de ver a página)
Route::get('/ferramentas/imt', function () {
    return view('tools.imt');
})->name('tools.imt');

// Simulador de Mais-Valias (Requer Backend)
// 1. Rota para mostrar o formulário
Route::get('/ferramentas/mais-valias', function () {
    return view('tools.gains');
})->name('tools.gains');

// 2. Rota OBRIGATÓRIA para fazer o cálculo (Esta era a que faltava!)
Route::post('/ferramentas/mais-valias/calcular', [ToolsController::class, 'calculateGains'])
    ->name('tools.gains.calculate');


// --- BLOG ---
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


// --- CONTACTOS ---
Route::get('/contato', function () {
    return view('contact');
})->name('contact');

// Rota para processar o envio do formulário de contacto (Home e Página Contacto)
Route::post('/contato', [ToolsController::class, 'sendContact'])->name('contact.submit');


// --- ADMINISTRAÇÃO ---
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