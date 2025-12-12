@extends('layouts.app')

@section('content')

<section class="relative h-screen w-full flex items-center justify-center overflow-hidden bg-ht-navy">
    
    {{-- Certifique-se de que o vídeo está em public/video/header_bg.mp4 --}}
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover">
        <source src="{{ asset('video/header_bg.mp4') }}" type="video/mp4">
    </video>
    
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-ht-navy via-transparent to-black/60"></div>

    <div class="relative z-10 container mx-auto px-6 text-center" data-aos="fade-up">
        
        <div x-data="{ texts: ['INTEGRIDADE', 'GRATIDÃO', 'LIBERDADE', 'CONFIANÇA'], idx: 0 }" 
             x-init="setInterval(() => idx = (idx + 1) % texts.length, 2500)"
             class="h-10 mb-4 overflow-hidden flex justify-center">
            <template x-for="(text, index) in texts">
                <p x-show="idx === index" 
                   x-transition:enter="transition ease-out duration-500"
                   x-transition:enter-start="opacity-0 translate-y-8"
                   x-transition:enter-end="opacity-100 translate-y-0"
                   x-transition:leave="transition ease-in duration-300"
                   x-transition:leave-start="opacity-100 translate-y-0"
                   x-transition:leave-end="opacity-0 -translate-y-8"
                   class="text-ht-blue font-black tracking-[0.5em] text-sm md:text-lg absolute drop-shadow-md">
                    <span x-text="text"></span>
                </p>
            </template>
        </div>

        <h1 class="text-6xl md:text-9xl font-black text-white leading-none tracking-tighter mb-10 drop-shadow-2xl">
            HOUSE<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-300">TEAM</span>
        </h1>

        <div class="mt-10 flex flex-col md:flex-row gap-6 justify-center items-center">
            <a href="{{ route('portfolio') }}" class="group relative px-8 py-4 bg-white text-ht-navy font-black uppercase tracking-widest text-xs rounded-full overflow-hidden shadow-[0_0_40px_-10px_rgba(255,255,255,0.3)] hover:scale-105 transition-transform duration-300">
                <span class="relative z-10 group-hover:text-ht-blue transition-colors">Ver Imóveis Exclusivos</span>
            </a>
            <a href="{{ route('contact') }}" class="px-8 py-4 border border-white/40 text-white font-bold uppercase tracking-widest text-xs rounded-full hover:bg-white/10 hover:border-white transition-all backdrop-blur-sm">
                Pedir Avaliação
            </a>
        </div>
    </div>

    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/70">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </div>
</section>

<section class="py-32 bg-slate-50 relative z-20 rounded-t-[3rem] -mt-10">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 px-2">
            <div>
                <span class="text-ht-blue font-bold text-xs uppercase tracking-widest mb-2 block">Portfólio</span>
                <h3 class="text-4xl font-black text-ht-navy tracking-tight">Oportunidades Recentes</h3>
            </div>
            <a href="{{ route('portfolio') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-ht-blue transition-colors mt-4 md:mt-0">
                Ver Todas 
                <span class="bg-slate-200 rounded-full w-6 h-6 flex items-center justify-center group-hover:bg-ht-blue group-hover:text-white transition-colors">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($properties as $property)
                <a href="{{ route('properties.show', $property) }}" class="group relative block h-[450px] rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                         class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/90 via-ht-navy/20 to-transparent opacity-80 group-hover:opacity-90 transition duration-500"></div>

                    <div class="absolute bottom-0 left-0 w-full p-8 text-white translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="flex justify-between items-start mb-3">
                            <span class="bg-ht-blue px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-lg">
                                {{ $property->status }}
                            </span>
                            <span class="text-xl font-black">{{ $property->price ? '€ ' . number_format($property->price, 0, ',', '.') : 'Consulte' }}</span>
                        </div>
                        
                        <h4 class="text-2xl font-bold mb-2 leading-tight line-clamp-2">{{ $property->title }}</h4>
                        <p class="text-sm text-slate-300 font-medium mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-ht-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $property->location }}
                        </p>
                        
                        <div class="flex gap-4 pt-4 border-t border-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-300">
                                <span>{{ $property->bedrooms }} Quartos</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-300">
                                <span>{{ number_format($property->area_gross, 0) }} m²</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold">Novos imóveis a chegar brevemente.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            
            <div class="lg:w-1/2 relative order-2 lg:order-1" data-aos="fade-right">
                <div class="absolute inset-0 bg-ht-navy transform -rotate-3 rounded-3xl translate-x-4 translate-y-4 opacity-10"></div>
                <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?q=80&w=1000&auto=format&fit=crop" 
                     alt="House Team Equipa" 
                     class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover grayscale hover:grayscale-0 transition duration-700">
            </div>

            <div class="lg:w-1/2 order-1 lg:order-2" data-aos="fade-left">
                <span class="text-ht-blue font-black text-xs uppercase tracking-widest mb-6 block">Sobre Nós</span>
                <h2 class="text-5xl font-black text-ht-navy mb-8 leading-tight tracking-tight">
                    House Team<br>Consultores
                </h2>
                <div class="space-y-6 text-slate-500 text-lg leading-relaxed font-medium">
                    <p>
                        Somos uma equipa experiente de Consultores Imobiliários da RE/MAX, pertencente ao Expogroup, fundada sob o conceito de <span class="text-ht-navy font-bold">Broker Empreendedor</span>.
                    </p>
                    <p>
                        Estamos totalmente disponíveis para acompanhá-lo em todas as etapas do seu projeto, seja na compra, venda ou arrendamento do seu futuro imóvel. Nossa missão é oferecer um serviço personalizado e de alta qualidade.
                    </p>
                </div>
                
                <div class="mt-12">
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-ht-navy text-white text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-ht-blue transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                        Fale Conosco
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-24 bg-ht-blue relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="bg-ht-navy rounded-[2.5rem] p-10 md:p-20 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-16 border border-white/10">
            <div class="lg:w-1/2 text-white">
                <h2 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    Quanto vale<br>o seu imóvel?
                </h2>
                <p class="text-blue-100 text-lg font-medium max-w-md mb-8">
                    Receba uma análise de mercado gratuita e precisa em 24h. Sem compromisso.
                </p>
            </div>

            <div class="lg:w-1/2 w-full bg-white p-8 rounded-3xl shadow-xl">
                <form action="{{ route('contact') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="subject" value="Avaliação Gratuita">
                    <h3 class="text-ht-navy font-bold text-xl mb-2">Peça a sua avaliação</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="name" placeholder="Nome" class="w-full bg-slate-50 border-slate-200 border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-ht-blue outline-none font-medium">
                        <input type="tel" name="phone" placeholder="Telemóvel" class="w-full bg-slate-50 border-slate-200 border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-ht-blue outline-none font-medium">
                    </div>
                    
                    <input type="text" name="location" placeholder="Morada do Imóvel" class="w-full bg-slate-50 border-slate-200 border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-ht-blue outline-none font-medium">

                    <button class="w-full bg-ht-blue text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-ht-navy transition-all shadow-lg mt-2">
                        Enviar Pedido
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h3 class="text-3xl font-black text-ht-navy mb-4">Ferramentas Inteligentes</h3>
            <p class="text-slate-500 max-w-xl mx-auto">Tome decisões financeiras informadas com os nossos simuladores exclusivos.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('tools.credit') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-blue group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Crédito Habitação</h4>
                <p class="text-sm text-slate-400 font-medium">Calcule a sua prestação mensal.</p>
            </a>
            
            <a href="{{ route('tools.gains') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-blue group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Mais-Valias</h4>
                <p class="text-sm text-slate-400 font-medium">Estime o imposto sobre a venda.</p>
            </a>

            <a href="{{ route('tools.imt') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-blue group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Simulador IMT</h4>
                <p class="text-sm text-slate-400 font-medium">Custos de aquisição e escritura.</p>
            </a>
        </div>
    </div>
</section>

@endsection