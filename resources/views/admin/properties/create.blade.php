<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Imóvel | House Team Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        'ht-navy': '#0f172a',
                        'ht-blue': '#2563eb',
                        'ht-light': '#f8fafc',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-ht-navy text-white flex flex-col shadow-2xl z-20">
            <div class="p-8 text-center border-b border-white/10">
                <h1 class="font-black text-2xl tracking-tighter">HOUSE TEAM<span class="text-ht-blue">.</span></h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold">Admin Panel</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Meus Imóveis
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8 md:p-12 overflow-y-auto bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-ht-navy tracking-tight">Novo Imóvel</h2>
                        <p class="text-slate-500 text-sm mt-1 font-medium">Preencha os dados para publicar um novo imóvel.</p>
                    </div>
                    <a href="{{ route('admin.properties.index') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-ht-blue transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Voltar
                    </a>
                </div>

                <form action="{{ route('admin.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">1</span>
                            Informações Básicas
                        </h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Título do Anúncio</label>
                                <input type="text" name="title" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: Moradia T4 de Luxo com Piscina">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tipo</label>
                                    <div class="relative">
                                        <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                            <option value="Apartamento">Apartamento</option>
                                            <option value="Moradia">Moradia / Villa</option>
                                            <option value="Terreno">Terreno</option>
                                            <option value="Comercial">Comercial</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Status</label>
                                    <div class="relative">
                                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                            <option value="Venda">Venda</option>
                                            <option value="Arrendamento">Arrendamento</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Preço (€)</label>
                                    <input type="number" name="price" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">2</span>
                            Detalhes do Imóvel
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Concelho / Zona</label>
                                <input type="text" name="location" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: Cascais, Quinta da Marinha">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Morada (Opcional)</label>
                                <input type="text" name="address" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Rua...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Área Bruta (m²)</label>
                                <input type="number" name="area_gross" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Quartos</label>
                                <input type="number" name="bedrooms" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Casas de Banho</label>
                                <input type="number" name="bathrooms" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Cert. Energética</label>
                                <div class="relative">
                                    <select name="energy_rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="">Selecione</option>
                                        <option value="A+">A+</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Andar</label>
                                <input type="text" name="floor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: 2º Esq, R/C">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Orientação Solar</label>
                                <div class="relative">
                                    <select name="orientation" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="">Selecione</option>
                                        <option value="Norte">Norte</option>
                                        <option value="Sul">Sul</option>
                                        <option value="Este">Este</option>
                                        <option value="Oeste">Oeste</option>
                                        <option value="Nascente/Poente">Nascente/Poente</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">3</span>
                            Comodidades
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="has_pool" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Piscina</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="has_garden" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Jardim</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="has_lift" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Elevador</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="has_terrace" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Terraço</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="has_air_conditioning" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Ar Condicionado</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="is_furnished" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Mobilado</span>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 cursor-pointer transition-all">
                                <input type="checkbox" name="is_kitchen_equipped" class="accent-ht-blue w-5 h-5 rounded">
                                <span class="text-sm font-medium text-slate-600">Cozinha Equipada</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">4</span>
                            Mídia e Links
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">WhatsApp (Nº Telemóvel)</label>
                                <input type="text" name="whatsapp_number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="351912345678">
                                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-bold">Insira o código do país sem + (Ex: 351...)</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tour YouTube (Link)</label>
                                <input type="url" name="video_url" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="https://youtube.com/watch?v=...">
                            </div>
                        </div>

                        <div class="mb-6 p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center hover:bg-slate-100 transition-colors">
                            <label class="block text-sm font-bold text-ht-navy mb-2">Foto de Capa (Principal)</label>
                            <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer">
                        </div>

                        <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center hover:bg-slate-100 transition-colors">
                            <label class="block text-sm font-bold text-ht-navy mb-2">Galeria de Fotos (Múltiplas)</label>
                            <input type="file" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-2 font-bold">Pressione Ctrl (ou Cmd) para selecionar várias fotos.</p>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Descrição Completa</label>
                        <textarea name="description" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all resize-y"></textarea>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-ht-blue text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95">
                            Publicar Imóvel
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>