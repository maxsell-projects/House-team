@extends('layouts.admin')

@section('title', 'Editar Empreendimento')
@section('header_title', 'Editar Empreendimento')

@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    <div class="max-w-5xl mx-auto pb-12">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-black text-ht-navy tracking-tight">Editar: {{ $development->title }}</h2>
                <p class="text-slate-500 text-sm mt-1 font-medium">Faça as alterações neste empreendimento.</p>
            </div>
            <a href="{{ route('admin.developments.index') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-ht-blue transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
                <p class="font-bold text-sm">Atenção:</p>
                <ul class="list-disc list-inside text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.developments.update', $development) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- 1. Informações Básicas -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">1</span>
                    Informações Básicas do Projeto
                </h3>
                
                <div class="grid grid-cols-1 gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Nome do Projeto</label>
                            <input type="text" name="title" value="{{ old('title', $development->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Ordem da Lista</label>
                            <input type="number" name="order" value="{{ old('order', $development->order) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                        </div>
                    </div>

                    {{-- DROPDOWN DE CONSULTORES --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Consultor Responsável</label>
                        
                        <div x-data="{ 
                                open: false, 
                                search: '', 
                                selectedId: '{{ $development->consultant_id }}', 
                                selectedName: '{{ $development->consultant ? $development->consultant->name : 'Selecione um Consultor' }}',
                                options: {{ $consultants->map(function($c) {
                                    $photoPath = ($c->photo && file_exists(public_path('storage/'.$c->photo))) 
                                        ? asset('storage/'.$c->photo) 
                                        : 'https://ui-avatars.com/api/?name='.urlencode($c->name).'&color=7F9CF5&background=EBF4FF';
                                    
                                    return [
                                        'id' => $c->id,
                                        'name' => $c->name,
                                        'photo' => $photoPath
                                    ];
                                })->toJson() }}
                             }" 
                             class="relative z-50">
                        
                            <input type="hidden" name="consultant_id" :value="selectedId">

                            <button type="button" 
                                    @click="open = !open" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-left flex items-center justify-between focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                <span x-text="selectedName" :class="selectedId ? 'text-ht-navy' : 'text-slate-400'"></span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl max-h-60 overflow-y-auto z-50 p-2"
                                 style="display: none;">
                                
                                <div class="px-2 pb-2 mb-2 border-b border-slate-100">
                                    <input x-model="search" 
                                           type="text" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-ht-blue" 
                                           placeholder="Pesquisar nome...">
                                </div>

                                <template x-for="option in options.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))" :key="option.id">
                                    <div @click="selectedId = option.id; selectedName = option.name; open = false"
                                         class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                                        <img :src="option.photo" class="w-8 h-8 rounded-full object-cover border border-slate-200" onerror="this.src='https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF'">
                                        <span class="text-sm font-medium text-ht-navy" x-text="option.name"></span>
                                    </div>
                                </template>
                                
                                <div x-show="options.filter(i => i.name.toLowerCase().includes(search.toLowerCase())).length === 0" class="p-3 text-xs text-slate-400 text-center">
                                    Nenhum consultor encontrado.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Estado da Obra</label>
                            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                <option value="Em construção" {{ old('status', $development->status) == 'Em construção' ? 'selected' : '' }}>Em construção</option>
                                <option value="Pronto" {{ old('status', $development->status) == 'Pronto' ? 'selected' : '' }}>Pronto</option>
                                <option value="Planta" {{ old('status', $development->status) == 'Planta' ? 'selected' : '' }}>Planta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tipologias</label>
                            <input type="text" name="typologies" value="{{ old('typologies', $development->typologies) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Áreas</label>
                            <input type="text" name="areas" value="{{ old('areas', $development->areas) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Ano Construção</label>
                            <input type="text" name="built_year" value="{{ old('built_year', $development->built_year) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Eficiência Energética</label>
                            <select name="energy_rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                <option value="">Não Aplicável</option>
                                <option value="A+" {{ old('energy_rating', $development->energy_rating) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A" {{ old('energy_rating', $development->energy_rating) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('energy_rating', $development->energy_rating) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('energy_rating', $development->energy_rating) == 'C' ? 'selected' : '' }}>C</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Latitude</label>
                            <input type="text" id="lat-input" name="latitude" value="{{ old('latitude', $development->latitude) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: 41.1579">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Longitude</label>
                            <input type="text" id="lng-input" name="longitude" value="{{ old('longitude', $development->longitude) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: -8.6291">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Localização no Mapa (Clique para preencher as coordenadas automaticamente)</label>
                        <div id="development-map-picker" class="w-full h-80 rounded-xl border border-slate-200 shadow-sm z-10" style="z-index: 10;"></div>
                    </div>
                </div>
            </div>

            <!-- 2. Arquivos (Brochuras) -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">2</span>
                    Ficheiros e Documentos (PDF)
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">
                        <label class="block text-sm font-bold text-ht-navy mb-2 text-center">Brochura</label>
                        @if($development->brochure_path)
                            <div class="mb-3 text-center">
                                <a href="{{ asset('storage/'.$development->brochure_path) }}" target="_blank" class="text-xs text-blue-600 underline font-semibold bg-blue-50 py-1 border border-blue-200 px-3 rounded-full">Ver Atual</a>
                            </div>
                        @endif
                        <input type="file" name="brochure" accept=".pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                    <div class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">
                        <label class="block text-sm font-bold text-ht-navy mb-2 text-center">Mapa de Acabamentos</label>
                        @if($development->finishes_map_path)
                            <div class="mb-3 text-center">
                                <a href="{{ asset('storage/'.$development->finishes_map_path) }}" target="_blank" class="text-xs text-blue-600 underline font-semibold bg-blue-50 py-1 border border-blue-200 px-3 rounded-full">Ver Atual</a>
                            </div>
                        @endif
                        <input type="file" name="finishes_map" accept=".pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                    <div class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">
                        <label class="block text-sm font-bold text-ht-navy mb-2 text-center">Ficha do Empreendimento</label>
                        @if($development->development_sheet_path)
                            <div class="mb-3 text-center">
                                <a href="{{ asset('storage/'.$development->development_sheet_path) }}" target="_blank" class="text-xs text-blue-600 underline font-semibold bg-blue-50 py-1 border border-blue-200 px-3 rounded-full">Ver Atual</a>
                            </div>
                        @endif
                        <input type="file" name="development_sheet" accept=".pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- 3. Galeria (Drag/Drop) -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">3</span>
                    Galeria de Fotos
                </h3>

                <input type="hidden" id="images_order" name="images_order" value="">
                <input type="hidden" id="cover_image_id" name="cover_image_id" value="">

                <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">
                    <label class="block text-sm font-bold text-ht-navy mb-2 ml-2">Adicionar Novas Imagens</label>
                    <input type="file" id="gallery-input" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-2 ml-2 font-bold italic">Arraste para reordenar. Pode clicar na estrela num item para defini-lo como Capa.</p>

                    <div id="gallery-preview" class="grid grid-cols-3 md:grid-cols-5 gap-4 mt-6">
                        {{-- Renderizando imagens salvas --}}
                        @foreach($development->photos as $photo)
                            <div class="relative h-24 w-full rounded-xl overflow-hidden shadow-sm border {{ $photo->is_cover ? 'border-amber-400 ring-2 ring-amber-400' : 'border-slate-200' }} group cursor-move transition-all bg-white existing-image" data-id="{{ $photo->id }}" data-is-cover="{{ $photo->is_cover ? 'true' : 'false' }}">
                                <img src="{{ asset('storage/'.$photo->path) }}" class="h-full w-full object-cover pointer-events-none">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                <div class="absolute inset-x-0 top-0 flex justify-between p-1">
                                    <button type="button" class="star-btn {{ $photo->is_cover ? 'text-amber-400' : 'text-slate-300 opacity-50' }} hover:text-amber-400 hover:opacity-100 drop-shadow-md">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </button>
                                    <button type="button" class="remove-btn text-red-500 hover:text-red-700 bg-white rounded-full w-5 h-5 flex items-center justify-center font-black shadow opacity-0 group-hover:opacity-100">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 4. Descrição -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">4</span>
                    Descrição Principal
                </h3>
                <textarea name="description" rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all resize-y">{{ old('description', $development->description) }}</textarea>
            </div>

            <!-- 4.1 O Bairro -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100" id="bairro-section">
                <h3 class="text-lg font-bold text-ht-navy mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">4.1</span>
                    O Bairro
                </h3>
                
                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Descrição da Região</label>
                    <textarea name="neighborhood_description" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all resize-y" placeholder="Descreva os destaques da região...">{{ old('neighborhood_description', $development->neighborhood_description) }}</textarea>
                </div>

                <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-xl hover:bg-slate-100 transition-colors">
                    <input type="hidden" id="neighborhood_images_order" name="neighborhood_images_order" value="">
                    
                    <label class="block text-sm font-bold text-ht-navy mb-2 ml-2">Galeria O Bairro</label>
                    <input type="file" id="neighborhood-gallery-input" name="neighborhood_gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-2 ml-2 font-bold italic">Arraste para reordenar as fotos do bairro.</p>

                    <div id="neighborhood-gallery-preview" class="grid grid-cols-3 md:grid-cols-5 gap-4 mt-6">
                        @foreach($development->neighborhoodPhotos as $photo)
                            <div class="relative h-24 w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 group cursor-move transition-all bg-white existing-n-image" data-id="{{ $photo->id }}">
                                <img src="{{ asset('storage/'.$photo->path) }}" class="h-full w-full object-cover pointer-events-none">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                <div class="absolute inset-x-0 top-0 flex justify-end p-1">
                                    <button type="button" class="remove-n-btn text-red-500 hover:text-red-700 bg-white rounded-full w-5 h-5 flex items-center justify-center font-black shadow opacity-0 group-hover:opacity-100">&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 5. Frações (Alpine JS Dynamics) -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100" x-data="fractionsManager()">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-ht-navy flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-ht-blue text-white flex items-center justify-center text-xs">5</span>
                        Tabela de Frações
                    </h3>
                    <button type="button" @click="addFraction()" class="bg-ht-navy text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-ht-blue transition-all flex items-center gap-2">
                        + Nova Fração
                    </button>
                </div>
                
                <div class="overflow-x-auto pb-4 bg-slate-50/50 rounded-xl p-2 border border-slate-100">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-slate-100 text-[10px] uppercase text-slate-500 font-extrabold tracking-wider">
                            <tr>
                                <th class="p-3 rounded-tl-lg">Ref</th>
                                <th class="p-3">Bloco</th>
                                <th class="p-3">Piso</th>
                                <th class="p-3">Tipologia</th>
                                <th class="p-3">ABP (m²)</th>
                                <th class="p-3">Varanda (m²)</th>
                                <th class="p-3">Estacionamentos</th>
                                <th class="p-3">ID Remax</th>
                                <th class="p-3">Preço (€)</th>
                                <th class="p-3">Planta</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center rounded-tr-lg">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="(frac, index) in fractions" :key="index">
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <input type="hidden" :name="`fractions[${index}][id]`" x-model="frac.id">
                                    <td class="p-2"><input type="text" :name="`fractions[${index}][ref]`" x-model="frac.ref" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm" placeholder="Ex: A"></td>
                                    <td class="p-2"><input type="text" :name="`fractions[${index}][block]`" x-model="frac.block" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm" placeholder="Ex: 1"></td>
                                    <td class="p-2"><input type="text" :name="`fractions[${index}][floor]`" x-model="frac.floor" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm" placeholder="Ex: R/C"></td>
                                    <td class="p-2"><input type="text" :name="`fractions[${index}][typology]`" x-model="frac.typology" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm" placeholder="Ex: T2"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="`fractions[${index}][abp]`" x-model="frac.abp" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="`fractions[${index}][balcony_area]`" x-model="frac.balcony_area" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm"></td>
                                    <td class="p-2"><input type="number" :name="`fractions[${index}][parking_spaces]`" x-model="frac.parking_spaces" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm"></td>
                                    <td class="p-2"><input type="text" :name="`fractions[${index}][remax_id]`" x-model="frac.remax_id" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm"></td>
                                    <td class="p-2"><input type="number" step="0.01" :name="`fractions[${index}][price]`" x-model="frac.price" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm"></td>
                                    <td class="p-2 text-center">
                                        <div class="flex flex-col items-center gap-1 w-full max-w-[80px]">
                                            <template x-if="frac.floor_plan_path">
                                                <a :href="`/storage/${frac.floor_plan_path}`" target="_blank" class="text-[9px] text-ht-blue bg-blue-50 px-2 py-1 rounded w-full border border-blue-100 font-bold truncate">Ver BD</a>
                                            </template>
                                            <input type="file" :name="`fractions[${index}][floor_plan]`" accept=".pdf,.png,.jpg,.jpeg" class="w-full text-[10px] text-slate-400 file:px-2 file:py-1 file:rounded file:border-0 file:bg-slate-100 file:text-slate-600 hover:file:bg-slate-200 text-transparent">
                                        </div>
                                    </td>
                                    <td class="p-2">
                                        <select :name="`fractions[${index}][status]`" x-model="frac.status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all bg-white shadow-sm">
                                            <option value="Disponível">Disponível</option>
                                            <option value="Reservado">Reservado</option>
                                            <option value="Vendido">Vendido</option>
                                        </select>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" @click="removeFraction(index)" title="Remover Fração" class="text-red-400 hover:text-white bg-red-50 hover:bg-red-500 rounded-lg w-8 h-8 flex items-center justify-center transition-colors mx-auto font-bold">X</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div x-show="fractions.length === 0" class="text-center text-slate-400 text-sm font-medium py-10 bg-white rounded-xl border border-dashed border-slate-200 mt-2">
                        Nenhuma fração adicionada ainda. Clique em "+ Nova Fração" para começar.
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 mb-20">
                <button type="submit" class="bg-ht-blue text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95">
                    Atualizar Empreendimento
                </button>
            </div>
        </form>
    </div>

    {{-- Script Leaflet JS para Map Picker --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // -- 1. Map Picker Setup --
            const latInput = document.getElementById('lat-input');
            const lngInput = document.getElementById('lng-input');
            
            let initLat = latInput && latInput.value ? parseFloat(latInput.value) : 39.3999;
            let initLng = lngInput && lngInput.value ? parseFloat(lngInput.value) : -8.2245;
            let initZoom = latInput && latInput.value ? 13 : 6;

            const map = L.map('development-map-picker').setView([initLat, initLng], initZoom);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let marker = null;
            if (latInput && latInput.value && lngInput && lngInput.value) {
                marker = L.marker([initLat, initLng]).addTo(map);
            }

            map.on('click', function(e) {
                const lat = e.latlng.lat.toFixed(6);
                const lng = e.latlng.lng.toFixed(6);
                
                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;
                
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });

            function updateMapFromInput() {
                if (latInput.value && lngInput.value) {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const newLatLng = new L.LatLng(lat, lng);
                        if (marker) {
                            marker.setLatLng(newLatLng);
                        } else {
                            marker = L.marker(newLatLng).addTo(map);
                        }
                        map.setView(newLatLng, 13);
                    }
                }
            }
            if (latInput) latInput.addEventListener('input', updateMapFromInput);
            if (lngInput) lngInput.addEventListener('input', updateMapFromInput);
        });
    </script>

    {{-- Script TUNADO com SortableJS, DataTransfer, e Update Fields --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('gallery-input');
            const previewContainer = document.getElementById('gallery-preview');
            const orderInput = document.getElementById('images_order');
            const coverInput = document.getElementById('cover_image_id');
            
            // Initial Order / Cover sync
            function syncExistingInputs() {
                const existingItems = Array.from(previewContainer.querySelectorAll('.existing-image'));
                const orderIds = existingItems.map(item => item.dataset.id);
                orderInput.value = orderIds.join(',');

                const coverItem = existingItems.find(item => item.dataset.isCover === 'true');
                coverInput.value = coverItem ? coverItem.dataset.id : '';
            }

            syncExistingInputs();

            // Setup Sortable
            new Sortable(previewContainer, {
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function() {
                    updateFileInput(); 
                    syncExistingInputs();
                }
            });

            // Bind events for existing items (remove & star)
            Array.from(previewContainer.querySelectorAll('.existing-image')).forEach(el => {
                const removeBtn = el.querySelector('.remove-btn');
                const starBtn = el.querySelector('.star-btn');
                
                removeBtn.addEventListener('click', () => {
                    el.remove();
                    syncExistingInputs();
                });

                starBtn.addEventListener('click', () => {
                    // Remove cover style from all
                    Array.from(previewContainer.querySelectorAll('.existing-image')).forEach(item => {
                        item.dataset.isCover = 'false';
                        item.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400');
                        item.classList.add('border-slate-200');
                        const sbtn = item.querySelector('.star-btn');
                        sbtn.classList.add('text-slate-300', 'opacity-50');
                        sbtn.classList.remove('text-amber-400');
                    });

                    // Add cover style to clicked
                    el.dataset.isCover = 'true';
                    el.classList.remove('border-slate-200');
                    el.classList.add('border-amber-400', 'ring-2', 'ring-amber-400');
                    starBtn.classList.remove('text-slate-300', 'opacity-50');
                    starBtn.classList.add('text-amber-400');

                    syncExistingInputs();
                });
            });

            // For new files added via the input file
            input.addEventListener('change', function() {
                Array.from(this.files).forEach(file => {
                    const div = document.createElement('div');
                    // New files just get uploaded. (If they want to make them cover immediately, 
                    // it requires a slightly complex flow to submit them first. We keep it simple: existing images can be covers).
                    div.className = "relative h-24 w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 group cursor-move hover:border-ht-blue transition-all bg-white new-image";
                    
                    div.file = file; 

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-full w-full object-cover pointer-events-none opacity-80 border-2 border-dashed border-green-400">
                            <button type="button" class="remove-new-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold shadow-md opacity-0 group-hover:opacity-100">&times;</button>
                            <div class="absolute bottom-0 inset-x-0 bg-green-500 text-white text-[8px] p-1 text-center font-bold">NOVA</div>
                        `;

                        div.querySelector('.remove-new-btn').addEventListener('click', function() {
                            div.remove();
                            updateFileInput(); 
                        });
                    };
                    reader.readAsDataURL(file);
                    previewContainer.appendChild(div);
                });
                updateFileInput();
                syncExistingInputs(); // Wait, this doesn't add to orders natively, but Sortable allows dragging new items too.
            });

            function updateFileInput() {
                const dt = new DataTransfer();
                const previewItems = previewContainer.querySelectorAll('.new-image');
                for (let i = 0; i < previewItems.length; i++) {
                    if (previewItems[i].file) {
                        dt.items.add(previewItems[i].file);
                    }
                }
                input.files = dt.files;
            }

            // Neighborhood setup
            const nInput = document.getElementById('neighborhood-gallery-input');
            const nPreviewContainer = document.getElementById('neighborhood-gallery-preview');
            const nOrderInput = document.getElementById('neighborhood_images_order');

            function syncNExistingInputs() {
                const existingItems = Array.from(nPreviewContainer.querySelectorAll('.existing-n-image'));
                const orderIds = existingItems.map(item => item.dataset.id);
                nOrderInput.value = orderIds.join(',');
            }
            syncNExistingInputs();

            new Sortable(nPreviewContainer, {
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function() {
                    updateNFileInput(); 
                    syncNExistingInputs();
                }
            });

            Array.from(nPreviewContainer.querySelectorAll('.existing-n-image')).forEach(el => {
                const removeBtn = el.querySelector('.remove-n-btn');
                removeBtn.addEventListener('click', () => {
                    el.remove();
                    syncNExistingInputs();
                });
            });

            nInput.addEventListener('change', function() {
                Array.from(this.files).forEach(file => {
                    const div = document.createElement('div');
                    div.className = "relative h-24 w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 group cursor-move hover:border-ht-blue transition-all bg-white new-n-image";
                    div.file = file; 
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-full w-full object-cover pointer-events-none opacity-80 border-2 border-dashed border-green-400">
                            <button type="button" class="remove-new-n-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold shadow-md opacity-0 group-hover:opacity-100">&times;</button>
                            <div class="absolute bottom-0 inset-x-0 bg-green-500 text-white text-[8px] p-1 text-center font-bold">NOVA</div>
                        `;
                        div.querySelector('.remove-new-n-btn').addEventListener('click', function() {
                            div.remove();
                            updateNFileInput(); 
                        });
                    };
                    reader.readAsDataURL(file);
                    nPreviewContainer.appendChild(div);
                });
                updateNFileInput();
                syncNExistingInputs(); 
            });

            function updateNFileInput() {
                const dt = new DataTransfer();
                const previewItems = nPreviewContainer.querySelectorAll('.new-n-image');
                for (let i = 0; i < previewItems.length; i++) {
                    if (previewItems[i].file) {
                        dt.items.add(previewItems[i].file);
                    }
                }
                nInput.files = dt.files;
            }
        });

        // Initialize Fractions from Laravel DB
        function fractionsManager() {
            return {
                fractions: {!! json_encode($development->fractions) !!} || [],
                addFraction() {
                    this.fractions.push({ 
                        id: null, ref: '', block: '', floor: '', typology: '', 
                        abp: '', balcony_area: '', parking_spaces: '', remax_id: '', price: '', status: 'Disponível'
                    });
                },
                removeFraction(index) {
                    this.fractions.splice(index, 1);
                }
            }
        }
    </script>

@endsection
