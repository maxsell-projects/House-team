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

                {{-- O BAIRRO --}}
                @if($development->neighborhood_description || $development->neighborhoodPhotos->count() > 0)
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('developments.neighborhood') }}</h3>
                    @if($development->neighborhood_description)
                    <div class="prose prose-lg prose-slate text-slate-500 font-medium leading-relaxed text-justify max-w-none mb-8">
                        {!! nl2br(e($development->neighborhood_description)) !!}
                    </div>
                    @endif

                    @if($development->neighborhoodPhotos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($development->neighborhoodPhotos as $nPhoto)
                            <a href="{{ asset('storage/' . $nPhoto->path) }}" target="_blank" class="block rounded-xl overflow-hidden aspect-video relative group">
                                <img src="{{ asset('storage/' . $nPhoto->path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                            </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                {{-- FRAÇÕES --}}
                @if($development->fractions->count() > 0)
                @php
                    $totalFractions = $development->fractions->count();
                    $soldFractions = $development->fractions->where('status', 'Vendido')->count();
                    $soldPercentage = $totalFractions > 0 ? round(($soldFractions / $totalFractions) * 100) : 0;
                    $fractionsJson = $development->fractions->map(function($f) {
                        return [
                            'id' => $f->id,
                            'ref' => $f->ref,
                            'block' => $f->block,
                            'floor' => $f->floor,
                            'typology' => $f->typology,
                            'abp' => $f->abp,
                            'balcony_area' => $f->balcony_area,
                            'price' => $f->price,
                            'status' => $f->status,
                            'floor_plan' => $f->floor_plan_path ? asset('storage/'.$f->floor_plan_path) : null,
                            'price_formatted' => $f->price ? '€ ' . number_format($f->price, 0, ',', '.') : __('developments.price_on_request'),
                            'remax_id' => $f->remax_id,
                            'parking' => $f->parking_spaces
                        ];
                    })->toJson();
                @endphp
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100" 
                     x-data="{ 
                        view: 'grid', 
                        filterStatus: 'all',
                        fractions: {{ $fractionsJson }},
                        showModal: false,
                        activeFrac: null,
                        openFrac(frac) {
                            this.activeFrac = frac;
                            this.showModal = true;
                        },
                        get filteredFractions() {
                            if(this.filterStatus === 'all') return this.fractions;
                            return this.fractions.filter(f => f.status === this.filterStatus);
                        }
                     }">
                     
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-ht-navy flex items-center gap-3">
                                {{ __('developments.fractions_map') }}
                                <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-bold">{{ $soldPercentage }}% {{ __('developments.sold_percent') }}</span>
                            </h3>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                            <!-- Quick Filter -->
                            <div class="flex p-1 bg-slate-100 rounded-xl w-full sm:w-auto overflow-x-auto scrollbar-hide">
                                <button @click="filterStatus = 'all'" :class="filterStatus === 'all' ? 'bg-white shadow text-ht-navy font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs flex-1 sm:flex-none whitespace-nowrap transition-all">{{ __('developments.filter_status_all') }}</button>
                                <button @click="filterStatus = 'Disponível'" :class="filterStatus === 'Disponível' ? 'bg-green-100 shadow text-green-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs flex-1 sm:flex-none whitespace-nowrap transition-all">{{ __('developments.status_available_plural') }}</button>
                                <button @click="filterStatus = 'Reservado'" :class="filterStatus === 'Reservado' ? 'bg-amber-100 shadow text-amber-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs flex-1 sm:flex-none whitespace-nowrap transition-all">{{ __('developments.status_reserved_plural') }}</button>
                                <button @click="filterStatus = 'Vendido'" :class="filterStatus === 'Vendido' ? 'bg-red-100 shadow text-red-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs flex-1 sm:flex-none whitespace-nowrap transition-all">{{ __('developments.status_sold_plural') }}</button>
                            </div>

                            <!-- View Toggle -->
                            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl shrink-0">
                                <button @click="view = 'grid'" :class="view === 'grid' ? 'bg-white shadow text-ht-navy' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                    <span class="hidden sm:inline">{{ __('developments.view_grid') }}</span>
                                </button>
                                <button @click="view = 'table'" :class="view === 'table' ? 'bg-white shadow text-ht-navy' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-2 rounded-lg text-xs font-bold flex items-center gap-2 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    <span class="hidden sm:inline">{{ __('developments.view_list') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- GRID VIEW --}}
                    <div x-show="view === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" style="display: none;" x-transition>
                        <template x-for="frac in filteredFractions" :key="frac.id">
                            <div @click="openFrac(frac)" 
                                 class="bg-white border rounded-[1.5rem] p-6 cursor-pointer group relative overflow-hidden transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl"
                                 :class="{
                                     'border-green-200 hover:border-green-400': frac.status === 'Disponível',
                                     'border-amber-200 hover:border-amber-400': frac.status === 'Reservado',
                                     'border-red-200 hover:border-red-400': frac.status === 'Vendido',
                                     'border-slate-200 hover:border-ht-accent': !frac.status
                                 }">
                                
                                <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-10 transition-transform group-hover:scale-150"
                                     :class="{
                                         'bg-green-500': frac.status === 'Disponível',
                                         'bg-amber-500': frac.status === 'Reservado',
                                         'bg-red-500': frac.status === 'Vendido',
                                         'bg-ht-accent': !frac.status
                                     }"></div>

                                <div class="flex justify-between items-start mb-6 relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-md"
                                             :class="{
                                                 'bg-gradient-to-br from-green-400 to-green-600': frac.status === 'Disponível',
                                                 'bg-gradient-to-br from-amber-400 to-amber-600': frac.status === 'Reservado',
                                                 'bg-gradient-to-br from-red-400 to-red-600': frac.status === 'Vendido',
                                                 'bg-gradient-to-br from-ht-navy to-slate-800': !frac.status
                                             }"
                                             x-text="frac.ref || '-'"></div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">{{ __('developments.floor') }} <span x-text="frac.floor || '-'"></span> &bull; {{ __('developments.block') }} <span x-text="frac.block || '-'"></span></p>
                                            <p class="font-black text-ht-navy text-lg" x-text="frac.typology || '-'"></p>
                                        </div>
                                    </div>
                                    
                                    <span x-show="frac.status === 'Disponível'" class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm border border-green-200">{{ __('developments.status_available') }}</span>
                                    <span x-show="frac.status === 'Reservado'" class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm border border-amber-200">{{ __('developments.status_reserved') }}</span>
                                    <span x-show="frac.status === 'Vendido'" class="bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm border border-red-200">{{ __('developments.status_sold') }}</span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm text-slate-500 mb-6 relative z-10">
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 hover:bg-white transition-colors">
                                        <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            {{ __('developments.area_abp') }}
                                        </span> 
                                        <span class="font-black text-ht-navy" x-text="frac.abp ? parseFloat(frac.abp) + ' m²' : '-'"></span>
                                    </div>
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 hover:bg-white transition-colors">
                                        <span class="block text-[10px] font-bold uppercase text-slate-400 mb-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            {{ __('developments.balcony') }}
                                        </span> 
                                        <span class="font-black text-ht-navy" x-text="frac.balcony_area ? parseFloat(frac.balcony_area) + ' m²' : '-'"></span>
                                    </div>
                                </div>

                                <div class="flex items-end justify-between border-t border-slate-100 pt-5 relative z-10 mt-auto">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">{{ __('developments.price') }}</p>
                                        <span class="font-black text-ht-navy text-xl" :class="frac.price ? '' : 'text-slate-400 text-sm'" x-text="frac.price_formatted"></span>
                                    </div>
                                    <span class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-ht-navy group-hover:bg-ht-navy group-hover:text-white transition-all shadow-sm">
                                        <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="filteredFractions.length === 0" class="col-span-full py-12 text-center bg-slate-50 rounded-[2rem] border border-dashed border-slate-200" style="display: none;">
                            <p class="text-slate-500 font-bold">{{ __('developments.no_fractions') }}</p>
                        </div>
                    </div>

                    {{-- TABLE VIEW --}}
                    <div x-show="view === 'table'" class="overflow-x-auto" x-transition>
                        <table class="w-full text-left border-collapse cursor-pointer">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                                    <th class="py-4 px-4 rounded-l-xl">{{ __('developments.table_ref') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_block') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_floor') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_typology') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_abp') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_balcony') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.parking') }}</th>
                                    <th class="py-4 px-4">{{ __('developments.table_price') }}</th>
                                    <th class="py-4 px-4 rounded-r-xl">{{ __('developments.table_status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                <template x-for="frac in filteredFractions" :key="frac.id">
                                    <tr @click="openFrac(frac)" class="hover:bg-slate-50 transition-colors group">
                                        <td class="py-4 px-4 font-black text-ht-navy" x-text="frac.ref || '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.block || '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.floor || '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.typology || '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.abp ? parseFloat(frac.abp) + ' m²' : '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.balcony_area ? parseFloat(frac.balcony_area) + ' m²' : '-'"></td>
                                        <td class="py-4 px-4 text-slate-500" x-text="frac.parking || '-'"></td>
                                        <td class="py-4 px-4 font-bold" :class="frac.price ? 'text-ht-navy' : 'text-slate-400'" x-text="frac.price_formatted"></td>
                                        <td class="py-4 px-4">
                                            <span x-show="frac.status === 'Disponível'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">{{ __('developments.status_available') }}</span>
                                            <span x-show="frac.status === 'Reservado'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">{{ __('developments.status_reserved') }}</span>
                                            <span x-show="frac.status === 'Vendido'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700">{{ __('developments.status_sold') }}</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- FRACTION MODAL --}}
                    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
                        <!-- Backdrop -->
                        <div x-show="showModal" 
                             @click="showModal = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-40"></div>

                        <!-- Panel -->
                        <div x-show="showModal"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                             class="bg-white rounded-3xl w-full max-w-4xl shadow-2xl relative z-50 overflow-hidden flex flex-col max-h-[90vh]">
                            
                            <!-- Header / Banner -->
                            <div class="h-32 relative bg-ht-navy flex items-end p-6 shrink-0">
                                @if($development->coverPhoto)
                                <img src="{{ asset('storage/'.$development->coverPhoto->path) }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
                                @endif
                                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 to-transparent"></div>
                                <div class="relative z-10 w-full flex justify-between items-end">
                                    <div>
                                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest mb-1">{{ $development->title }}</p>
                                        <h4 class="text-white text-3xl font-black">{{ __('developments.fraction') }} <span x-text="activeFrac?.ref"></span></h4>
                                    </div>
                                    <button @click="showModal = false" class="bg-black/50 hover:bg-black/80 text-white rounded-full p-2 backdrop-blur transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="flex-1 overflow-y-auto p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                                <!-- Details Column -->
                                <div class="space-y-6">
                                    <div>
                                        <h5 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{{ __('developments.fraction_info') }}</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.typology') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.typology || '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.block') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.block || '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.floor') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.floor || '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.area_gross') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.abp ? parseFloat(activeFrac.abp) + ' m²' : '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.balcony_terrace') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.balcony_area ? parseFloat(activeFrac.balcony_area) + ' m²' : '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.parking') }}</span>
                                                <span class="font-bold text-ht-navy" x-text="activeFrac?.parking || '-'"></span>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                <span class="block text-[10px] uppercase text-slate-400 font-bold">{{ __('developments.status') }}</span>
                                                <span class="font-bold" 
                                                    :class="{
                                                        'text-green-600': activeFrac?.status === 'Disponível',
                                                        'text-amber-600': activeFrac?.status === 'Reservado',
                                                        'text-red-600': activeFrac?.status === 'Vendido'
                                                    }" 
                                                    x-text="activeFrac?.status"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('developments.price') }}</span>
                                        <span class="text-2xl font-black text-ht-navy" x-text="activeFrac?.price_formatted"></span>
                                    </div>

                                    <div x-show="activeFrac?.floor_plan">
                                        <h5 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">{{ __('developments.floor_plan_label') ?? 'Planta' }}</h5>
                                        
                                        <!-- Floor Plan: Image Preview (jpg, png, jpeg) -->
                                        <template x-if="activeFrac?.floor_plan && !activeFrac.floor_plan.toLowerCase().endsWith('.pdf')">
                                            <a :href="activeFrac?.floor_plan" target="_blank" class="block mb-3 rounded-xl overflow-hidden border border-slate-200 bg-white hover:shadow-lg transition-shadow group/plan relative">
                                                <img :src="activeFrac?.floor_plan" alt="Planta da Fração" class="w-full h-auto object-contain max-h-[300px] p-2">
                                                <div class="absolute inset-0 bg-black/0 group-hover/plan:bg-black/10 transition-colors flex items-center justify-center">
                                                    <span class="opacity-0 group-hover/plan:opacity-100 transition-opacity bg-white/90 backdrop-blur text-ht-navy px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                        {{ __('developments.view_full_size') ?? 'Ver tamanho real' }}
                                                    </span>
                                                </div>
                                            </a>
                                        </template>

                                        <!-- Floor Plan: PDF Embed -->
                                        <template x-if="activeFrac?.floor_plan && activeFrac.floor_plan.toLowerCase().endsWith('.pdf')">
                                            <div class="mb-3 rounded-xl overflow-hidden border border-slate-200 bg-white">
                                                <iframe :src="activeFrac?.floor_plan" class="w-full h-[300px] border-0"></iframe>
                                            </div>
                                        </template>

                                        <!-- Download / Open Link -->
                                        <a :href="activeFrac?.floor_plan" target="_blank" class="flex items-center gap-3 bg-ht-blue/10 text-ht-blue p-3 rounded-xl hover:bg-ht-blue hover:text-white transition-colors group/dl">
                                            <svg class="w-6 h-6 opacity-80 group-hover/dl:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <div>
                                                <p class="font-bold text-sm">{{ __('developments.download_plan') }}</p>
                                                <p class="text-[10px] uppercase tracking-widest opacity-80">{{ __('developments.view_file') }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <!-- Form Column -->
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                    <h5 class="text-sm font-black text-ht-navy mb-1">{{ __('developments.interest_fraction') }}</h5>
                                    <p class="text-xs text-slate-500 font-medium mb-6">{{ __('developments.fill_form_fraction') }} <span x-text="activeFrac?.ref"></span>.</p>
                                    
                                    <form action="{{ route('front.developments.fractionContact') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="development_title" value="{{ $development->title }}">
                                        <input type="hidden" name="fraction_ref" :value="activeFrac?.ref">
                                        <input type="hidden" name="remax_id" :value="activeFrac?.remax_id">

                                        <div>
                                            <input type="text" name="name" required placeholder="{{ __('developments.form_name') }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue">
                                        </div>
                                        <div>
                                            <input type="email" name="email" required placeholder="{{ __('developments.form_email') }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue">
                                        </div>
                                        <div>
                                            <input type="text" name="phone" required placeholder="{{ __('developments.form_phone') }}" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue">
                                        </div>
                                        <div>
                                            <button type="submit" class="w-full bg-ht-accent text-white font-bold uppercase tracking-widest py-3 text-xs rounded-xl hover:bg-ht-blue transition-colors flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                {{ __('developments.form_submit') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif
                
                {{-- LOCALIZAÇÃO (MAPS) --}}
                @if($development->latitude && $development->longitude)
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('developments.location') }}</h3>
                    <div class="rounded-xl overflow-hidden shadow-inner border border-slate-100 bg-slate-50 w-full h-[400px] relative z-10" id="development-map"></div>
                </div>

                <!-- Leaflet JS -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var lat = {{ $development->latitude }};
                        var lng = {{ $development->longitude }};
                        
                        var map = L.map('development-map').setView([lat, lng], 15);
                        
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                            subdomains: 'abcd',
                            maxZoom: 20
                        }).addTo(map);

                        // Custom Marker Icon
                        var customIcon = L.divIcon({
                            className: 'custom-leaflet-marker',
                            html: `<div class="w-10 h-10 bg-ht-accent rounded-full flex items-center justify-center text-white shadow-xl shadow-blue-500/30 border-4 border-white pointer-events-none">
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                   </div>`,
                            iconSize: [40, 40],
                            iconAnchor: [20, 40],
                            popupAnchor: [0, -40]
                        });

                        var marker = L.marker([lat, lng], {icon: customIcon}).addTo(map)
                            .bindPopup(`<b>{{ addslashes($development->title) }}</b><br>Ver no mapa.`)
                            .openPopup();
                    });
                </script>
                <style>
                    .custom-leaflet-marker {
                        background: transparent;
                        border: none;
                    }
                </style>
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
                                <a href="{{ route('contact', ['development' => $development->title]) }}" class="block w-full bg-ht-accent text-white font-black uppercase tracking-widest py-4 text-xs rounded-lg hover:bg-ht-blue transition-all text-center shadow-lg">
                                    {{ __('developments.btn_contact') }}
                                </a>
                            </div>
                        </div>

                        {{-- CONSULTOR (Se associado) --}}
                        @if($development->consultant_id && $development->consultant)
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center text-center mt-6">
                            <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-ht-accent mb-4 shadow-lg">
                                <img src="{{ $development->consultant->photo ? $development->consultant->image_url : 'https://ui-avatars.com/api/?name='.urlencode($development->consultant->name).'&color=7F9CF5&background=EBF4FF' }}" class="w-full h-full object-cover">
                            </div>
                            <h4 class="text-lg font-black text-ht-navy mb-1">{{ $development->consultant->name }}</h4>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{{ __('properties.consultant_title') ?? 'Consultor' }}</p>
                            
                            <div class="w-full space-y-3">
                                <a href="tel:{{ $development->consultant->phone ?? '+351912345678' }}" class="flex items-center justify-center gap-2 w-full py-3 bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 hover:border-slate-200 transition-colors cursor-pointer text-sm font-bold text-ht-navy">
                                    <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $development->consultant->phone ?? 'Ligar' }}
                                </a>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $development->consultant->phone ?? '351912345678') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-[#25D366]/10 text-[#25D366] rounded-xl hover:bg-[#25D366]/20 transition-colors font-bold text-sm">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.878-.788-1.472-1.761-1.645-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.85 11.85 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                        @endif
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

{{-- EMPREENDIMENTOS RECOMENDADOS --}}
@if($recommended && $recommended->count() > 0)
<div class="py-20 bg-white">
    <div class="container mx-auto px-6 md:px-12">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-ht-navy mb-4">{{ __('developments.recommended_title') ?? 'Empreendimentos Recomendados' }}</h2>
                <p class="text-slate-500 max-w-2xl">{{ __('developments.recommended_desc') ?? 'Descubra outras oportunidades exclusivas que selecionamos para si.' }}</p>
            </div>
            <a href="{{ route('developments.index') }}" class="hidden md:flex items-center gap-2 text-ht-accent font-bold text-sm uppercase tracking-wider hover:text-ht-navy transition-colors">
                {{ __('developments.view_all') ?? 'Ver Todos' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($recommended as $rec)
                @php
                    $cover = $rec->photos->where('is_cover', true)->first();
                    if(!$cover) $cover = $rec->photos->first();
                @endphp
                <a href="{{ route('developments.show', $rec->slug) }}" class="group block bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 relative">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $cover ? asset('storage/' . $cover->path) : asset('img/bg-hero.jpg') }}" alt="{{ $rec->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                        
                        @if($rec->status)
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur text-ht-navy px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider shadow-lg">
                                {{ __($rec->status) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-8">
                        <div class="flex items-center gap-2 text-ht-accent mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-xs font-bold uppercase tracking-wider truncate">{{ $rec->location ?? 'Portugal' }}</span>
                        </div>
                        
                        <h3 class="text-2xl font-black text-ht-navy mb-4 group-hover:text-ht-accent transition-colors line-clamp-1">
                            {{ $rec->title }}
                        </h3>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-4">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                                {{ __('developments.view_details') ?? 'Ver Detalhes' }} 
                                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('developments.index') }}" class="inline-flex items-center gap-2 text-ht-accent font-bold text-sm uppercase tracking-wider hover:text-ht-navy transition-colors">
                {{ __('developments.view_all') ?? 'Ver Todos' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</div>
@endif

{{-- Estilos para o iframe do Google Maps (Removido, Leaflet em uso) --}}

@endsection
