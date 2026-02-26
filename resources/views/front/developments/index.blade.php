@extends('layouts.app')

@section('title', __('developments.title') . ' - House Team')

@section('content')

{{-- HERO HEADER --}}
<div class="bg-ht-navy pt-32 text-white pb-20 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">
            {{ __('developments.badge') }}
        </p>
        <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">
            {{ __('developments.title') }}
        </h1>
        <p class="text-slate-400 font-medium max-w-xl mx-auto">
            {{ __('developments.subtitle') }}
        </p>
    </div>
</div>

<section class="py-16 bg-slate-50 min-h-screen relative z-20 -mt-10">
    <div class="container mx-auto px-6 md:px-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR FILTROS --}}
            <aside class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100 sticky top-32">
                    <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                        <h3 class="font-bold text-xl text-ht-navy">{{ __('developments.filter_title') }}</h3>
                        <a href="{{ route('developments.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-ht-accent transition-colors">{{ __('developments.filter_clear') }}</a>
                    </div>
                    
                    <form action="{{ route('developments.index') }}" method="GET" class="space-y-6">
                        
                        {{-- Nome ou Localização --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('developments.filter_name') }}</label>
                            <div class="relative">
                                <input type="text" name="location" value="{{ request('location') }}" placeholder="{{ __('developments.filter_name_placeholder') }}" 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all placeholder-slate-400">
                                <svg class="w-4 h-4 absolute right-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        {{-- Finalidade / Estado --}}
                        @if(isset($statuses) && $statuses->count() > 0)
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('developments.filter_status') }}</label>
                            <div class="relative">
                                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent appearance-none transition-all cursor-pointer">
                                    <option value="">{{ __('developments.filter_status_all') }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ __($status) }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        @endif

                        <button type="submit" class="w-full text-white font-black uppercase tracking-widest text-xs py-4 rounded-xl transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95 bg-ht-accent hover:bg-blue-600">
                            {{ __('developments.btn_filter') }}
                        </button>
                    </form>
                </div>
            </aside>

            {{-- GRID DE EMPREENDIMENTOS --}}
            <div class="lg:col-span-3">
                <div class="flex justify-between items-center mb-8 px-2">
                    <p class="text-slate-500 text-sm font-medium">{{ __('developments.showing') }} <span class="text-ht-navy font-bold">{{ $developments->total() }}</span> {{ __('developments.count') }}</p>
                    <div class="hidden md:block text-xs font-bold uppercase tracking-widest text-slate-400">
                        {{ __('developments.order_recent') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($developments as $development)
                        <a href="{{ route('developments.show', $development->slug) }}" class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-slate-100">
                            
                            <div class="relative h-64 overflow-hidden">
                                @php
                                    $cover = $development->photos->where('is_cover', true)->first() ?? $development->photos->first();
                                @endphp
                                @if($cover)
                                    <img src="{{ asset('storage/' . $cover->path) }}" 
                                         alt="{{ $development->title }}" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 left-4 bg-slate-900 text-white px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider shadow-md">
                                    {{ __('developments.label_project') }}
                                </div>
                                @if($development->status)
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 text-[10px] font-bold text-ht-navy rounded-full uppercase tracking-wider shadow-sm">
                                    {{ __($development->status) }}
                                </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h4 class="text-xl font-black text-ht-navy mb-4 line-clamp-2 leading-tight group-hover:text-ht-accent transition-colors">
                                    {{ $development->title }}
                                </h4>

                                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500 border-t border-slate-100 pt-4 mb-4">
                                    @if($development->typologies)
                                        <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg" title="{{ __('developments.label_typologies') }}">
                                            <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            {{ $development->typologies }}
                                        </span>
                                    @endif
                                    @if($development->areas)
                                        <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg" title="{{ __('developments.label_areas') }}">
                                            <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            {{ $development->areas }}
                                        </span>
                                    @endif
                                    @if($development->built_year)
                                        <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg" title="{{ __('developments.label_year') }}">
                                            <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $development->built_year }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-400">{{ __('developments.view_project') }}</p>
                                        <p class="text-sm font-black text-ht-accent mt-1">
                                            {{ __('developments.more_details') }}
                                        </p>
                                    </div>
                                    <span class="w-8 h-8 rounded-full bg-slate-100 text-ht-navy flex items-center justify-center group-hover:bg-ht-navy group-hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center py-20 bg-white rounded-[2rem] border border-dashed border-slate-300">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-slate-500 font-medium">{{ __('developments.no_results') }}</p>
                            <a href="{{ route('developments.index') }}" class="text-ht-accent font-bold text-sm hover:underline mt-2 inline-block">{{ __('developments.filter_clear') }}</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $developments->links() }}
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
