@extends('layouts.app')

@section('content')

<section class="relative py-32 bg-brand-black text-white text-center">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-gold/10 rounded-full blur-3xl"></div>
        <div class="absolute top-20 -left-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 container mx-auto px-6">
        <p class="text-brand-gold text-xs uppercase tracking-[0.4em] mb-4">Insights & Tendências</p>
        <h1 class="text-5xl md:text-7xl font-serif">Journal</h1>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6 md:px-12">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center mb-24">
            <div class="relative group cursor-pointer overflow-hidden rounded-sm" data-aos="fade-right">
                <a href="{{ route('blog.show') }}">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 z-10"></div>
                    <img src="{{ asset('img/DiogoMaia2.jpg') }}" 
                         alt="Diogo Maia" 
                         class="w-full h-[500px] object-cover transform group-hover:scale-105 transition duration-700 grayscale-[10%]">
                </a>
            </div>
            
            <div data-aos="fade-left">
                <div class="flex items-center gap-4 mb-6">
                    <span class="bg-brand-black text-white text-[10px] uppercase tracking-widest px-3 py-1">Destaque</span>
                    <span class="text-gray-400 text-xs uppercase tracking-widest">Tendências 2025</span>
                </div>
                
                <h2 class="text-3xl md:text-5xl font-serif text-brand-black mb-6 leading-tight hover:text-brand-gold transition-colors duration-300">
                    <a href="{{ route('blog.show') }}">
                        O Novo Perfil do Investidor de Luxo: Discrição, Património e Propósito
                    </a>
                </h2>
                
                <p class="text-gray-500 font-light text-lg leading-relaxed mb-8">
                    O mercado de luxo está a mudar. Já não se define apenas pelo preço ou tamanho. O verdadeiro investidor de 2025 procura algo mais profundo: estabilidade, privacidade e um sentido de legado.
                </p>
                
                <a href="{{ route('blog.show') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-[0.2em] text-brand-black hover:text-brand-gold transition-colors border-b border-gray-300 pb-1 hover:border-brand-gold">
                    Ler Artigo Completo
                </a>
            </div>
        </div>

        <div class="border-t border-gray-100 mb-16"></div>

        <div class="mb-12 text-center" data-aos="fade-up">
            <h3 class="text-3xl font-serif text-brand-black">Últimas Publicações</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl mx-auto">
            
            <article class="group flex flex-col h-full" data-aos="fade-up" data-aos-delay="0">
                <div class="h-64 overflow-hidden mb-6 bg-gray-100 rounded-sm relative">
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 text-[10px] uppercase tracking-widest z-10">Inteligência</span>
                    <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition duration-700 group-hover:scale-105 grayscale group-hover:grayscale-0" alt="Dados">
                </div>
                <div class="flex-grow flex flex-col">
                    <h4 class="text-xl font-serif mb-4 leading-snug group-hover:text-brand-gold transition">
                        <a href="{{ route('blog.show-intelligence') }}">
                            Como a Inteligência de Mercado Redefine o Investimento Imobiliário
                        </a>
                    </h4>
                    <p class="text-sm text-gray-500 font-light leading-relaxed mb-6 flex-grow line-clamp-3">
                        O investimento imobiliário de luxo deixou de depender apenas da intuição. A diferença entre uma compra promissora e uma perda está nos dados.
                    </p>
                    <a href="{{ route('blog.show-intelligence') }}" class="text-[10px] uppercase tracking-widest font-bold hover:text-brand-gold mt-auto inline-block">Ler Mais »</a>
                </div>
            </article>

            <article class="group flex flex-col h-full" data-aos="fade-up" data-aos-delay="100">
                <div class="h-64 overflow-hidden mb-6 bg-gray-100 rounded-sm relative">
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 text-[10px] uppercase tracking-widest z-10">Localização</span>
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover transition duration-700 group-hover:scale-105 grayscale group-hover:grayscale-0" alt="Portugal">
                </div>
                <div class="flex-grow flex flex-col">
                    <h4 class="text-xl font-serif mb-4 leading-snug group-hover:text-brand-gold transition">
                        <a href="{{ route('blog.show-locations') }}">
                            Lisboa, Cascais e Algarve: Os Três Eixos de Valor em 2025
                        </a>
                    </h4>
                    <p class="text-sm text-gray-500 font-light leading-relaxed mb-6 flex-grow line-clamp-3">
                        Zonas como Lisboa, Cascais e Algarve destacam-se como três eixos de valor que sustentam o crescimento premium do país.
                    </p>
                    <a href="{{ route('blog.show-locations') }}" class="text-[10px] uppercase tracking-widest font-bold hover:text-brand-gold mt-auto inline-block">Ler Mais »</a>
                </div>
            </article>

        </div>
    </div>
</section>

@endsection