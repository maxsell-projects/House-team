@extends('layouts.app')

@section('title', $development->title . ' - ' . __('developments.title') . ' - House Team')

@section('content')

{{-- 
    CONTAINER PRINCIPAL COM LÓGICA DE IMAGENS REORGANIZADA 
--}}
<div class="pt-32 pb-12 bg-slate-50 relative" 
     x-data="{ 
        isModalOpen: false, 
        activeImage: '{{ $development->photos->where('is_cover', true)->first() ? asset('storage/' . $development->photos->where('is_cover', true)->first()->path) : ($development->photos->first() ? asset('storage/' . $development->photos->first()->path) : asset('img/porto.jpg')) }}',
        images: [
            @if($development->photos->where('is_cover', true)->first())
                '{{ asset('storage/' . $development->photos->where('is_cover', true)->first()->path) }}',
            @endif
            @foreach($development->photos->where('is_cover', false)->sortBy('order') as $img)
                '{{ asset('storage/' . $img->path) }}',
            @endforeach
        ],
        currentIndex: 0,
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            this.activeImage = this.images[this.currentIndex];
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            this.activeImage = this.images[this.currentIndex];
        },
        setImage(index) {
            this.currentIndex = index;
            this.activeImage = this.images[index];
        }
    }"
    @keydown.escape.window="isModalOpen = false"
    @keydown.arrow-right.window="if(isModalOpen) next()"
    @keydown.arrow-left.window="if(isModalOpen) prev()"
>
    
    <div class="container mx-auto px-6 md:px-12 relative z-10">
        
        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10" data-aos="fade-up">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-ht-accent text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/30">
                        {{ __('developments.badge') }}
                    </span>
                    @if($development->status)
                    <span class="bg-white text-ht-navy border border-slate-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ __($development->status) }}
                    </span>
                    @endif
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-ht-navy leading-tight max-w-4xl">
                    {{ $development->title }}
                </h1>
            </div>
            
            <div class="text-right hidden md:block">
                <a href="{{ route('developments.index') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-ht-accent transition-colors">{{ __('developments.back_to_list') }}</a>
            </div>
        </div>

        {{-- GALERIA DE IMAGENS ORDENADA --}}
        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl bg-slate-900 group mb-16 h-[50vh] md:h-[70vh]" data-aos="zoom-in">
            <div class="absolute inset-0 transition-all duration-700 ease-in-out cursor-zoom-in" @click="isModalOpen = true">
                <img :src="activeImage" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500" alt="{{ $development->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/80 via-transparent to-transparent"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/50 backdrop-blur-md text-white px-6 py-3 rounded-full flex items-center gap-2 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    <span class="text-xs font-bold uppercase tracking-widest">{{ __('portfolio.zoom_image') }}</span>
                </div>
            </div>

            {{-- Setas --}}
            <button @click.stop="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform -translate-x-4 group-hover:translate-x-0 z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click.stop="next()" class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Miniaturas Reais --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 overflow-x-auto max-w-[90%] p-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/10 z-20 scrollbar-hide">
                <template x-for="(img, index) in images" :key="index">
                    <button @click.stop="setImage(index)" 
                            class="relative w-16 h-12 md:w-20 md:h-14 rounded-xl overflow-hidden transition-all duration-300 transform hover:scale-105 flex-shrink-0"
                            :class="currentIndex === index ? 'ring-2 ring-ht-accent opacity-100 scale-105' : 'opacity-60 hover:opacity-100'">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 relative">
            
            {{-- COLUNA ESQUERDA (Info Principal) --}}
            <div class="lg:col-span-8 space-y-12">
                
                {{-- DESTAQUES --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="block text-xl font-black text-ht-navy">{{ $development->typologies ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('developments.label_typologies') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </div>
                        <span class="block text-xl font-black text-ht-navy">{{ $development->areas ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('developments.label_areas') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="block text-xl font-black text-ht-navy">{{ $development->built_year ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('developments.label_year') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="block text-xl font-black text-ht-navy">{{ $development->energy_rating ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('developments.label_energy') }}</span>
                    </div>
                </div>

                {{-- DESCRIÇÃO --}}
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('developments.about') }}</h3>
                    <div class="prose prose-lg prose-slate text-slate-500 font-medium leading-relaxed text-justify max-w-none">
                        {!! nl2br(e($development->description)) !!}
                    </div>
                </div>

                {{-- FRAÇÕES --}}
                @if($development->fractions->count() > 0)
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('developments.fractions_map') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                                    <th class="py-4 px-4 rounded-l-xl">{{ __('developments.table_ref') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_floor') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_typology') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_abp') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_balcony') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_price') }}</th>
                                    <th class="py-4 px-4 rounded-r-xl">{{ __('developments.table_status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                @foreach($development->fractions as $fraction)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-4">{{ $fraction->ref ?: '-' }}</td>
                                    <td class="py-4 px-4">{{ $fraction->floor ?: '-' }}</td>
                                    <td class="py-4 px-4">{{ $fraction->typology ?: '-' }}</td>
                                    <td class="py-4 px-4">{{ $fraction->abp ? $fraction->abp . ' m²' : '-' }}</td>
                                    <td class="py-4 px-4">{{ $fraction->balcony_area ? $fraction->balcony_area . ' m²' : '-' }}</td>
                                    <td class="py-4 px-4 font-bold {{ $fraction->price ? 'text-ht-navy' : 'text-slate-400' }}">{{ $fraction->price ? '€ ' . number_format($fraction->price, 0, ',', '.') : __('developments.price_on_request') }}</td>
                                    <td class="py-4 px-4">
                                        @if($fraction->status == 'Disponível')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">{{ __('developments.status_available') }}</span>
                                        @elseif($fraction->status == 'Reservado')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">{{ __('developments.status_reserved') }}</span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700">{{ __('developments.status_sold') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
                {{-- LOCALIZAÇÃO (MAPS) --}}
                @if($development->latitude)
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('developments.location') }}</h3>
                    <div class="rounded-xl overflow-hidden shadow-inner border border-slate-100 bg-slate-50 w-full h-[400px]">
                        {!! $development->latitude !!}
                    </div>
                </div>
                @endif
            </div>

            {{-- COLUNA DIREITA (Sidebar Sticky) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-6">
                    
                    <div class="bg-ht-navy text-white p-8 rounded-[2rem] shadow-2xl border border-white/10 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-ht-accent/30 rounded-full blur-3xl"></div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-6 relative z-10">{{ __('developments.documents') }}</p>
                        
                        <div class="space-y-4 relative z-10">
                            @if($development->brochure_path)
                            <a href="{{ asset('storage/' . $development->brochure_path) }}" target="_blank" class="flex items-center justify-between bg-white/5 hover:bg-white/10 border border-white/10 p-4 rounded-xl transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <div>
                                        <p class="font-bold text-sm">{{ __('developments.doc_brochure') }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ __('developments.doc_download') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @endif

                            @if($development->finishes_map_path)
                            <a href="{{ asset('storage/' . $development->finishes_map_path) }}" target="_blank" class="flex items-center justify-between bg-white/5 hover:bg-white/10 border border-white/10 p-4 rounded-xl transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <div>
                                        <p class="font-bold text-sm">{{ __('developments.doc_finishes') }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ __('developments.doc_download') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @endif

                            @if($development->development_sheet_path)
                            <a href="{{ asset('storage/' . $development->development_sheet_path) }}" target="_blank" class="flex items-center justify-between bg-white/5 hover:bg-white/10 border border-white/10 p-4 rounded-xl transition-colors">
                                <div class="flex items-center gap-3">
                                    <svg class="w-8 h-8 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <div>
                                        <p class="font-bold text-sm">{{ __('developments.doc_sheet') }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ __('developments.doc_download') }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                            @endif

                            @if(!$development->brochure_path && !$development->finishes_map_path && !$development->development_sheet_path)
                                <p class="text-sm text-slate-400 text-center py-4">{{ __('developments.doc_empty') }}</p>
                            @endif
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10">
                            <a href="{{ route('contact', ['development' => $development->title]) }}" class="block w-full bg-ht-accent text-white font-black uppercase tracking-widest py-4 text-xs rounded-lg hover:bg-[#b08d48] transition-all text-center shadow-lg">
                                {{ __('developments.btn_contact') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LIGHTBOX --}}
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-black/95 p-4 md:p-10"
         x-cloak>
        <button @click="isModalOpen = false" class="absolute top-6 right-6 text-white hover:text-ht-accent transition-colors z-[210]">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button @click.stop="prev()" class="absolute left-4 md:left-10 top-1/2 -translate-y-1/2 text-white hover:text-ht-accent p-4 z-[210] transition-transform hover:-translate-x-1">
            <svg class="w-10 h-10 md:w-16 md:h-16 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click.stop="next()" class="absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-white hover:text-ht-accent p-4 z-[210] transition-transform hover:translate-x-1">
            <svg class="w-10 h-10 md:w-16 md:h-16 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
        </button>
        <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" @click.away="isModalOpen = false">
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/50 text-xs font-bold uppercase tracking-widest text-center">
            {{ $development->title }} <br>
            <span class="text-[10px] opacity-70" x-text="(currentIndex + 1) + ' / ' + images.length"></span>
        </div>
    </div>
</div>

{{-- Estilos para o iframe do Google Maps --}}
<style>
    .rounded-xl.overflow-hidden iframe {
        width: 100% !important;
        height: 100% !important;
        border: 0 !important;
    }
</style>

@endsection
