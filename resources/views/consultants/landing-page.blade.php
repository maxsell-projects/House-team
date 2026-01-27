@extends('layouts.app')

@section('title', $consultant->name . ' - ' . __('consultant_lp.hero_subtitle'))

@section('content')

{{-- DESIGN SYSTEM: NAVY & GOLD --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

    :root {
        --color-navy: #1e293b;
        --color-navy-light: #334155;
        --color-gold: #c5a059;
        --color-gold-light: #e5c585;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Inter', sans-serif;
    }

    body { font-family: var(--font-sans); color: var(--color-navy); }
    h1, h2, h3, h4, .font-serif { font-family: var(--font-serif); }

    .text-gold { color: var(--color-gold) !important; }
    .text-navy { color: var(--color-navy) !important; }
    .bg-gold { background-color: var(--color-gold) !important; }
    .bg-navy { background-color: var(--color-navy) !important; }
    .border-gold { border-color: var(--color-gold) !important; }

    /* Custom Focus Ring for Gold */
    .focus-ring-gold:focus {
        --tw-ring-color: var(--color-gold);
        --tw-ring-opacity: 0.5;
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(3px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        border-color: var(--color-gold);
    }
    
    /* Custom Checkbox/Radio Accent */
    .accent-gold { accent-color: var(--color-gold); }

    .btn-gold {
        background-color: var(--color-gold);
        color: white;
        padding: 14px 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 12px;
        transition: all 0.3s ease;
        border: 1px solid var(--color-gold);
        display: inline-block;
        text-align: center;
    }
    .btn-gold:hover {
        background-color: transparent;
        color: var(--color-gold);
        transform: translateY(-2px);
    }

    .btn-navy-outline {
        background-color: transparent;
        color: var(--color-navy);
        padding: 14px 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 12px;
        transition: all 0.3s ease;
        border: 1px solid var(--color-navy);
        display: inline-block;
    }
    .btn-navy-outline:hover {
        background-color: var(--color-navy);
        color: white;
        transform: translateY(-2px);
    }

    .decor-line {
        height: 2px;
        width: 60px;
        background-color: var(--color-gold);
        margin: 20px 0;
    }

    .property-card { transition: transform 0.4s ease, box-shadow 0.4s ease; }
    .property-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(30, 41, 59, 0.1); }
    
    /* Estilo para os cards de sobre */
    .about-card {
        background-color: var(--color-navy);
        padding: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .about-card:hover { transform: translateY(-5px); }
    .about-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background-color: var(--color-gold);
    }
</style>

<div class="overflow-x-hidden">

    {{-- POPUP DE SUCESSO --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl p-8 max-w-md text-center shadow-2xl relative border-t-4 border-gold">
            <button @click="show = false" class="absolute top-4 right-4 text-slate-400 hover:text-gold">✕</button>
            <div class="w-16 h-16 bg-yellow-50 text-gold rounded-full flex items-center justify-center mx-auto mb-4 border border-gold">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-2xl font-serif font-bold text-navy mb-2">Pedido Recebido!</h3>
            <p class="text-slate-500 text-sm">Obrigado pelo seu contacto. Eu e a minha equipa iremos analisar o seu imóvel e entraremos em contacto brevemente.</p>
            <button @click="show = false" class="mt-6 btn-gold w-full rounded">Fechar</button>
        </div>
    </div>
    @endif

    {{-- 1. HERO SECTION --}}
    <section id="home" class="relative min-h-screen flex items-center pt-28 pb-20 bg-slate-50">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-navy hidden lg:block" style="clip-path: polygon(20% 0%, 100% 0, 100% 100%, 0% 100%);"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1" data-aos="fade-right" data-aos-duration="1000">
                    
                    <span class="text-gold font-bold tracking-[0.2em] text-xs uppercase mb-4 block">
                        {{ __('consultant_lp.hero_subtitle') }}
                    </span>
                    
                    <h1 class="text-6xl lg:text-7xl font-bold text-navy leading-tight mb-6">
                        {{ $consultant->name }}
                    </h1>
                    
                    <div class="decor-line"></div>

                    <p class="text-slate-600 text-lg leading-relaxed mb-10 font-light max-w-lg">
                        {{ __('consultant_lp.hero_desc') }}
                    </p>

                    <div class="flex gap-4">
                        <a href="#portfolio" class="btn-gold shadow-lg">
                            {{ __('consultant_lp.btn_collection') }}
                        </a>
                        <a href="#contact" class="btn-navy-outline">
                            {{ __('consultant_lp.btn_contact') }}
                        </a>
                    </div>
                </div>

                <div class="order-1 lg:order-2 relative" data-aos="fade-left" data-aos-duration="1000">
                    <div class="relative z-10">
                        <img src="{{ $consultant->image_url ?? asset('img/team/' . $consultant->photo) }}" 
                             alt="{{ $consultant->name }}" 
                             class="w-full h-auto object-cover shadow-2xl rounded-sm max-h-[80vh] ml-auto"
                             style="box-shadow: -20px 20px 0px rgba(197, 160, 89, 0.2);"
                             onerror="this.src='{{ asset('img/logo.png') }}'">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. ESTATÍSTICAS --}}
    <section class="bg-navy py-16 text-white relative">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/10">
                <div data-aos="fade-up" data-aos-delay="0">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">15+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_years') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">1M€+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_dreams') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">1.000+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_producer') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">50+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_dedication') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SOBRE --}}
    <section id="about" class="py-24 bg-white">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-slate-400 text-xs font-bold uppercase tracking-widest block mb-3">{{ __('consultant_lp.why_title_label') }}</span>
                <h2 class="text-4xl text-navy mb-6">{{ __('consultant_lp.why_title') }}</h2>
                <div class="w-20 h-[1px] bg-gold mx-auto"></div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Blocos de Sobre mantidos --}}
                <div class="about-card shadow-xl" data-aos="fade-up" data-aos-delay="0">
                    <div class="mb-6 text-gold">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-serif mb-4 text-gold-light">{{ __('consultant_lp.about_card_1_title') }}</h3>
                    <p class="text-sm font-light leading-relaxed text-slate-300">
                        {{ __('consultant_lp.about_card_1_text') }}
                    </p>
                </div>

                <div class="about-card shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="mb-6 text-gold">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <h3 class="text-xl font-serif mb-4 text-gold-light">{{ __('consultant_lp.about_card_2_title') }}</h3>
                    <p class="text-sm font-light leading-relaxed text-slate-300">
                        {{ __('consultant_lp.about_card_2_text') }}
                    </p>
                </div>

                <div class="about-card shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="mb-6 text-gold">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-serif mb-4 text-gold-light">{{ __('consultant_lp.about_card_3_title') }}</h3>
                    <p class="text-sm font-light leading-relaxed text-slate-300">
                        {{ __('consultant_lp.about_card_3_text') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. PORTFOLIO --}}
    <section id="portfolio" class="py-24 bg-slate-50">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div>
                    <span class="text-gold font-bold tracking-widest text-xs uppercase mb-2 block">{{ __('consultant_lp.properties_subtitle') }}</span>
                    <h2 class="text-4xl text-navy font-serif">{{ __('consultant_lp.properties_title') }}</h2>
                </div>
                <a href="https://houseteamconsultores.pt/imoveis" target="_blank" class="text-navy border-b border-navy pb-1 text-xs font-bold uppercase hover:text-gold hover:border-gold transition">
                    {{ __('consultant_lp.view_inventory') }}
                </a>
            </div>

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($properties as $property)
                        <a href="{{ route('properties.show', $property) }}?cid={{ $consultant->id }}" target="_blank" class="property-card group block bg-white shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-1000 ease-out">
                                
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="bg-navy text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest">
                                        {{ __('property_status.' . \Illuminate\Support\Str::slug($property->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-8 border-b-4 border-transparent group-hover:border-gold transition-colors">
                                <p class="text-gold text-xs font-bold uppercase tracking-wider mb-2">
                                    {{ $property->location }}
                                </p>
                                <h3 class="text-xl font-serif text-navy mb-4 group-hover:text-gold transition-colors truncate">
                                    {{ $property->title }}
                                </h3>
                                <div class="flex justify-between items-center mt-6">
                                    <span class="text-lg font-bold text-navy">
                                        {{ $property->price ? number_format($property->price, 0, ',', '.') . ' €' : 'Sob Consulta' }}
                                    </span>
                                    <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-gold group-hover:text-gold transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center">
                    <p class="text-slate-400 font-serif italic text-xl">{{ __('consultant_lp.portfolio_empty') }}</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 5. TESTEMUNHOS --}}
    <section id="testimonials" class="py-24 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <span class="text-slate-400 font-bold tracking-widest text-xs uppercase mb-10 block">{{ __('consultant_lp.feedback_title') }}</span>
            <div class="max-w-5xl mx-auto min-h-[400px]" data-aos="fade-up">
                <script type='text/javascript' src='https://reputationhub.site/reputation/assets/review-widget.js'></script>
                <iframe class='lc_reviews_widget' 
                        src='https://reputationhub.site/reputation/widgets/review_widget/pNqZN0PDS8KBp1b3Uk13?widgetId=69176d9fcf2958bcfa34b132' 
                        frameborder='0' scrolling='no' style='min-width: 100%; width: 100%; min-height: 400px; border: none;'>
                </iframe>
            </div>
        </div>
    </section>

    {{-- 6. AVALIAÇÃO E CONTACTO (WIZARD IGUAL AO SITE AGENTE) --}}
    <section id="contact" class="relative py-24 bg-navy overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold opacity-10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

        <div class="container mx-auto px-6 max-w-4xl relative z-10" data-aos="fade-up">
            <div class="text-center mb-16">
                <h2 class="text-4xl text-white font-serif mb-6">{{ __('consultant_lp.contact_title') }}</h2>
                <p class="text-slate-400 text-lg font-light">{{ __('consultant_lp.contact_subtitle') }}</p>
            </div>

            {{-- WIZARD START --}}
            <div x-data="{ 
                step: 1,
                type: 'apartamento',
                year: '',
                area: '',
                bedrooms: 2,
                bathrooms: 1,
                garages: 0, 
                parking_type: '',
                features: [],
                features_text: '',
                condition: '',
                name: '',
                email: '',
                phone: '',
                is_owner: '',
                
                validateStep1() {
                    if(!this.year || !this.area || !this.parking_type) {
                        alert('Por favor, preencha o Ano, Área e Tipo de Estacionamento.');
                        return false;
                    }
                    this.step++;
                },
                validateStep2() {
                    if(!this.condition) {
                        alert('Por favor, selecione o Estado de Conservação.');
                        return false;
                    }
                    this.step++;
                }
            }" class="bg-white rounded-sm shadow-2xl relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-2 bg-gold"></div>

                <form action="{{ route('contact') }}" method="POST">
                    @csrf
                    <input type="hidden" name="subject" value="Avaliação Detalhada de Imóvel">
                    {{-- Mantemos o consultant_id caso a rota precise, embora o domínio resolva --}}
                    <input type="hidden" name="consultant_id" value="{{ $consultant->id }}">
                    
                    {{-- Barra de Progresso --}}
                    <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                        <div class="flex gap-2">
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="step >= 1 ? 'w-8 bg-gold' : 'w-2 bg-slate-200'"></div>
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="step >= 2 ? 'w-8 bg-gold' : 'w-2 bg-slate-200'"></div>
                            <div class="h-1.5 rounded-full transition-all duration-500" :class="step >= 3 ? 'w-8 bg-gold' : 'w-2 bg-slate-200'"></div>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('valuation.step') }} <span x-text="step"></span>/3</span>
                    </div>

                    <div class="p-8 md:p-12">
                        
                        {{-- PASSO 1 --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
                            <h3 class="text-2xl font-serif text-navy mb-8">{{ __('valuation.section_details') }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <label class="cursor-pointer">
                                    <input type="radio" name="property_type" value="Casa" class="peer sr-only" x-model="type">
                                    <div class="p-6 rounded-xl border border-slate-200 text-center peer-checked:border-gold peer-checked:bg-yellow-50/50 transition-all hover:border-gold/50">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span class="font-bold text-sm text-slate-600 peer-checked:text-navy">{{ __('valuation.type_house') }}</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="property_type" value="Apartamento" class="peer sr-only" x-model="type">
                                    <div class="p-6 rounded-xl border border-slate-200 text-center peer-checked:border-gold peer-checked:bg-yellow-50/50 transition-all hover:border-gold/50">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span class="font-bold text-sm text-slate-600 peer-checked:text-navy">{{ __('valuation.type_flat') }}</span>
                                    </div>
                                </label>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_year') }} <span class="text-red-500">*</span></label>
                                    <input type="number" name="year" x-model="year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:outline-none focus-ring-gold transition-shadow" placeholder="Ex: 2010">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_area') }} <span class="text-red-500">*</span></label>
                                    <input type="number" name="area" x-model="area" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:outline-none focus-ring-gold transition-shadow" placeholder="Ex: 120">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_bedrooms') }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                        <button type="button" @click="bedrooms = Math.max(0, bedrooms - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-navy hover:text-gold transition">-</button>
                                        <input type="hidden" name="bedrooms" :value="bedrooms">
                                        <span class="font-bold text-lg text-navy" x-text="bedrooms"></span>
                                        <button type="button" @click="bedrooms++" class="w-8 h-8 rounded-lg bg-gold text-white shadow hover:bg-navy transition">+</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_bathrooms') }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                        <button type="button" @click="bathrooms = Math.max(0, bathrooms - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-navy hover:text-gold transition">-</button>
                                        <input type="hidden" name="bathrooms" :value="bathrooms">
                                        <span class="font-bold text-lg text-navy" x-text="bathrooms"></span>
                                        <button type="button" @click="bathrooms++" class="w-8 h-8 rounded-lg bg-gold text-white shadow hover:bg-navy transition">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_garage_type') }} <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="parking_type" x-model="parking_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:outline-none focus-ring-gold transition-shadow appearance-none text-slate-600">
                                            <option value="" disabled selected>{{ __('valuation.select_placeholder') }}</option>
                                            <option value="Sem Parqueamento">Sem Parqueamento</option>
                                            <option value="Garagem (Box)">{{ __('valuation.garage_box') }}</option>
                                            <option value="Lugar de Parqueamento">{{ __('valuation.parking_spot') }}</option>
                                            <option value="Estacionamento Exterior">{{ __('valuation.parking_exterior') }}</option>
                                        </select>
                                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                         </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_places') }}</label>
                                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                        <button type="button" @click="garages = Math.max(0, garages - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-navy hover:text-gold transition">-</button>
                                        <input type="hidden" name="garages" :value="garages">
                                        <span class="font-bold text-lg text-navy" x-text="garages"></span>
                                        <button type="button" @click="garages++" class="w-8 h-8 rounded-lg bg-gold text-white shadow hover:bg-navy transition">+</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- PASSO 2 --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-2xl font-serif text-navy mb-6">{{ __('valuation.section_features') }}</h3>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                                @php
                                    $featuresMap = [
                                        'Terraço' => 'feat_terrace', 
                                        'Mobilado' => 'feat_furnished', 
                                        'Piscina' => 'feat_pool', 
                                        'Estacionamento' => 'feat_parking', 
                                        'Jardim' => 'feat_garden', 
                                        'Condomínio fechado' => 'feat_gated', 
                                        'Boas vistas' => 'feat_views', 
                                        'Ar condicionado' => 'feat_ac', 
                                        'Aquecimento' => 'feat_heating', 
                                        'Domótica' => 'feat_domotics'
                                    ];
                                @endphp
                                @foreach($featuresMap as $dbValue => $transKey)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="features[]" value="{{ $dbValue }}" class="accent-gold w-4 h-4 rounded">
                                        <span class="text-xs font-bold text-slate-600">{{ __('valuation.' . $transKey) }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mb-6">
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Outras Características / Notas</label>
                                <textarea name="features_text" x-model="features_text" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus-ring-gold resize-none" placeholder="Ex: Precisa de pequenas obras na cozinha..."></textarea>
                            </div>

                            <div class="space-y-3">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">{{ __('valuation.label_condition') }} <span class="text-red-500">*</span></p>
                                @php
                                    $conditionsMap = [
                                        'Novo/Renovado (Design)' => 'cond_new', 
                                        'Bons Acabamentos' => 'cond_good_finish', 
                                        'Bom Estado' => 'cond_good', 
                                        'Pequena Reforma' => 'cond_minor_reform', 
                                        'Reforma Completa' => 'cond_total_reform'
                                    ];
                                @endphp
                                @foreach($conditionsMap as $dbValue => $transKey)
                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-gold cursor-pointer group">
                                        <input type="radio" name="condition" value="{{ $dbValue }}" x-model="condition" class="accent-gold w-4 h-4">
                                        <span class="text-sm font-medium text-slate-600 group-hover:text-navy">{{ __('valuation.' . $transKey) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PASSO 3 --}}
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                            <h3 class="text-2xl font-serif text-navy mb-6">{{ __('valuation.section_data') }}</h3>
                            
                            <div class="space-y-4">
                                <input type="text" name="address" placeholder="{{ __('valuation.placeholder_address') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus-ring-gold font-medium">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="name" required placeholder="{{ __('valuation.placeholder_name') }} *" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus-ring-gold font-medium">
                                    <input type="email" name="email" required placeholder="{{ __('valuation.placeholder_email') }} *" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus-ring-gold font-medium">
                                </div>

                                <div>
                                    <input type="tel" name="phone" x-model="phone" required placeholder="Telemóvel *" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus-ring-gold font-medium">
                                </div>

                                <div class="p-4 bg-yellow-50/50 rounded-xl border border-yellow-100">
                                    <p class="text-xs font-bold text-navy mb-2">{{ __('valuation.label_owner') }}</p>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="is_owner" value="Sim" class="accent-gold"> <span class="text-sm">{{ __('valuation.yes') }}</span></label>
                                        <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="is_owner" value="Não" class="accent-gold"> <span class="text-sm">{{ __('valuation.no') }}</span></label>
                                    </div>
                                </div>

                                <input type="number" name="estimated_value" placeholder="{{ __('valuation.placeholder_value') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus-ring-gold font-medium">

                                <label class="flex items-start gap-2 mt-4 cursor-pointer">
                                    <input type="checkbox" required class="accent-gold mt-1">
                                    <span class="text-[10px] text-slate-400 leading-tight">{{ __('valuation.terms_agree') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between items-center">
                            <button type="button" @click="step--" x-show="step > 1" class="text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-gold transition">← {{ __('valuation.btn_back') }}</button>
                            <div x-show="step === 1"></div> 
                            
                            {{-- BOTÕES --}}
                            <button type="button" @click="validateStep1()" x-show="step === 1" class="btn-gold rounded">
                                {{ __('valuation.btn_next') }}
                            </button>
                            <button type="button" @click="validateStep2()" x-show="step === 2" class="btn-gold rounded">
                                {{ __('valuation.btn_next') }}
                            </button>
                            
                            <button type="submit" x-show="step === 3" class="btn-gold rounded shadow-lg" style="display: none;">
                                {{ __('valuation.btn_submit') }}
                            </button>
                        </div>

                    </div>
                </form>
            </div>
            {{-- WIZARD END --}}

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-navy text-white py-12 text-center border-t border-white/5" style="background-color: #0f172a;">
        <div class="container mx-auto px-6">
            <img src="{{ asset('img/logo/casaacasa.png') }}" alt="Logo" class="h-16 mx-auto mb-6 brightness-0 invert opacity-50" onerror="this.style.display='none';">
            <p class="font-serif font-bold text-xl mb-2">{{ $consultant->name }}</p>
            <p class="text-xs text-slate-500 uppercase tracking-widest">&copy; {{ date('Y') }} • {{ __('consultant_lp.footer_note') }}</p>
        </div>
    </footer>

</div>

@endsection