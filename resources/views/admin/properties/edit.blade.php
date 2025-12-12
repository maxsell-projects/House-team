<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imóvel | House Team Admin</title>
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
    <div class="flex h-screen">
        <aside class="w-64 bg-ht-navy text-white flex flex-col shadow-2xl z-20">
            <div class="p-8 text-center border-b border-white/10">
                <h1 class="font-black text-2xl tracking-tighter">HOUSE TEAM<span class="text-ht-blue">.</span></h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold">Admin Panel</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all">
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    Meus Imóveis
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8 md:p-12 overflow-y-auto">
            <div class="max-w-5xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-black text-ht-navy tracking-tight">Editar Imóvel</h2>
                    <a href="{{ route('admin.properties.index') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-ht-blue transition-colors flex items-center gap-2">← Voltar</a>
                </div>

                <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Informações Básicas</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Título</label>
                                <input type="text" name="title" value="{{ $property->title }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tipo</label>
                                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="Apartamento" {{ $property->type == 'Apartamento' ? 'selected' : '' }}>Apartamento</option>
                                        <option value="Moradia" {{ $property->type == 'Moradia' ? 'selected' : '' }}>Moradia / Villa</option>
                                        <option value="Terreno" {{ $property->type == 'Terreno' ? 'selected' : '' }}>Terreno</option>
                                        <option value="Comercial" {{ $property->type == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Status</label>
                                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="Venda" {{ $property->status == 'Venda' ? 'selected' : '' }}>Venda</option>
                                        <option value="Arrendamento" {{ $property->status == 'Arrendamento' ? 'selected' : '' }}>Arrendamento</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Preço (€)</label>
                                    <input type="number" name="price" value="{{ $property->price }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Localização e Detalhes</h3>
                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Zona</label>
                                <input type="text" name="location" value="{{ $property->location }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Morada</label>
                                <input type="text" name="address" value="{{ $property->address }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Área (m²)</label>
                                <input type="number" name="area_gross" value="{{ $property->area_gross }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Quartos</label>
                                <input type="number" name="bedrooms" value="{{ $property->bedrooms }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">WC</label>
                                <input type="number" name="bathrooms" value="{{ $property->bathrooms }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Energia</label>
                                <select name="energy_rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                    <option value="A+" {{ $property->energy_rating == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A" {{ $property->energy_rating == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $property->energy_rating == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ $property->energy_rating == 'C' ? 'selected' : '' }}>C</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Comodidades</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="has_pool" {{ $property->has_pool ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Piscina</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="has_garden" {{ $property->has_garden ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Jardim</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="has_lift" {{ $property->has_lift ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Elevador</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="has_terrace" {{ $property->has_terrace ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Terraço</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="has_air_conditioning" {{ $property->has_air_conditioning ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Ar Condicionado</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="is_furnished" {{ $property->is_furnished ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Mobilado</span></label>
                            <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer"><input type="checkbox" name="is_kitchen_equipped" {{ $property->is_kitchen_equipped ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded"><span class="text-sm font-medium">Cozinha Equipada</span></label>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Mídia e Imagens</h3>
                        
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">WhatsApp</label>
                                <input type="text" name="whatsapp_number" value="{{ $property->whatsapp_number }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tour YouTube</label>
                                <input type="url" name="video_url" value="{{ $property->video_url }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                        </div>

                        <div class="flex items-center gap-6 mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="w-20 h-20 bg-slate-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($property->cover_image)
                                    <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">Sem Foto</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-ht-navy mb-2">Alterar Capa</label>
                                <input type="file" name="cover_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer">
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center hover:bg-slate-100 transition-colors">
                            <label class="block text-sm font-bold text-ht-navy mb-2">Adicionar Mais Fotos à Galeria</label>
                            <input type="file" name="gallery[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Descrição</label>
                        <textarea name="description" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all resize-y">{{ $property->description }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-ht-blue text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg transform active:scale-95">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>