@extends('layouts.app')

@section('content')

<section class="relative h-[50vh] min-h-[400px] flex items-end justify-start bg-fixed bg-cover bg-center" 
         style="background-image: url('https://images.unsplash.com/photo-1555881400-74d7acaacd81?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/30"></div>
    
    <div class="relative z-10 container mx-auto px-6 pb-16" data-aos="fade-up">
        <div class="max-w-4xl">
            <div class="flex items-center gap-4 mb-6 text-white/80">
                <span class="uppercase tracking-[0.2em] text-xs border border-white/30 px-3 py-1">Localização</span>
                <span class="text-xs uppercase tracking-widest">14 Dezembro, 2025</span>
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-serif text-white leading-tight">
                Lisboa, Cascais e Algarve: Os Três Eixos de Valor do Imobiliário Premium em 2025
            </h1>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
            
            <div class="lg:col-span-8" data-aos="fade-up">
                <div class="prose prose-lg prose-headings:font-serif prose-headings:text-brand-black prose-p:text-gray-600 prose-p:font-light prose-p:leading-relaxed prose-a:text-brand-gold max-w-none">
                    
                    <p class="text-xl md:text-2xl font-serif text-brand-black leading-relaxed mb-10 border-l-4 border-brand-gold pl-6 italic">
                        "O mercado imobiliário de luxo em Portugal vive uma fase de maturidade e expansão. Zonas como Lisboa, Cascais e Algarve destacam-se como três eixos de valor que sustentam o crescimento premium do país."
                    </p>

                    <p>
                        A combinação entre estabilidade económica, atratividade internacional e qualidade de vida continua a posicionar Portugal como um dos destinos de investimento mais seguros e desejados da Europa.
                    </p>
                    <p>
                        A seguir, analisamos os fatores que definem cada um desses polos e o que torna cada região única para investidores que procuram segurança, exclusividade e valorização sustentável.
                    </p>

                    <h2 class="mt-12 text-3xl">Lisboa: Estabilidade e Crescimento</h2>
                    <p>
                        Lisboa mantém-se como o coração do mercado imobiliário de luxo em Portugal. O seu equilíbrio entre tradição, modernidade e rentabilidade atrai tanto investidores locais quanto estrangeiros.
                    </p>
                    <p>
                        Em 2025, zonas como Lapa, Príncipe Real e Estrela continuam a registar um aumento consistente no valor por metro quadrado, impulsionado pela escassez de imóveis de qualidade e pela procura constante de residentes internacionais.
                    </p>
                    <p>
                        Além disso, o investimento em reabilitação urbana e mobilidade reforça a perceção de Lisboa como uma cidade global, onde o luxo se traduz em história preservada e conveniência moderna. A capital portuguesa permanece como o eixo de segurança e estabilidade para quem procura preservar e multiplicar o património.
                    </p>

                    <h2 class="mt-12 text-3xl">Cascais: Exclusividade e Discrição</h2>
                    <p>
                        Cascais é sinónimo de exclusividade. Conhecida pelo seu equilíbrio entre elegância, natureza e privacidade, a vila continua a ser o refúgio preferido de executivos, empresários e famílias que valorizam discrição e bem-estar.
                    </p>
                    <p>
                        Em 2025, as propriedades de luxo em Cascais mantêm uma taxa de valorização constante, sustentada pela limitação de oferta e pela procura de residências que combinam arquitetura contemporânea, vista para o mar e proximidade com Lisboa.
                    </p>
                    <p>
                        Os bairros da Quinta da Marinha, Birre e Monte Estoril continuam no topo da preferência dos compradores premium. Mais do que um endereço, viver em Cascais é um símbolo de equilíbrio entre qualidade de vida e investimento inteligente.
                    </p>

                    <h2 class="mt-12 text-3xl">Algarve: Rentabilidade e Lifestyle Internacional</h2>
                    <p>
                        O Algarve é o eixo do estilo de vida e da rentabilidade. A região consolidou-se como destino de eleição para investidores internacionais, especialmente britânicos, alemães e norte-americanos, atraídos pela segurança jurídica, clima ameno e rentabilidade em arrendamentos sazonais.
                    </p>
                    <p>
                        Vilamoura, Lagos e Quinta do Lago são os exemplos mais expressivos da nova geração de propriedades de luxo que unem conforto, sustentabilidade e retorno financeiro.
                    </p>
                    <p>
                        Com a expansão de voos internacionais e a melhoria contínua das infraestruturas locais, o Algarve reforça-se como polo de investimento seguro uma região onde o luxo é tangível e o retorno, previsível.
                    </p>

                    <h2 class="mt-12 text-3xl">Portugal como Destino de Património Seguro</h2>
                    <p>
                        O ponto comum entre Lisboa, Cascais e Algarve é a consolidação de Portugal como destino de património seguro. O país combina estabilidade política, segurança jurídica e qualidade de vida incomparável, fatores que sustentam a confiança dos investidores mesmo em cenários globais de incerteza.
                    </p>
                    <p>
                        A procura pelo mercado imobiliário de luxo em Portugal continua a crescer, impulsionada por uma visão moderna de investimento: menos especulativa e mais orientada para legado, diversificação e propósito.
                    </p>
                    <p>
                        O futuro do mercado imobiliário de luxo em Portugal passa pela consolidação dos três eixos estratégicos que sustentam a sua atratividade. Lisboa garante estabilidade, Cascais representa exclusividade e o Algarve traduz rentabilidade e lifestyle.
                    </p>
                    <p class="font-medium text-brand-black mt-8">
                        Para o investidor criterioso, compreender o comportamento destas zonas é essencial para tomar decisões com inteligência e visão de longo prazo. Portugal já não é apenas um destino é uma estratégia de património.
                    </p>

                </div>
                
                <div class="mt-16 pt-10 border-t border-gray-200 flex justify-between items-center">
                    <a href="{{ route('blog') }}" class="text-sm font-bold uppercase tracking-widest text-gray-500 hover:text-brand-black transition flex items-center gap-2">
                        ← Voltar ao Blog
                    </a>
                    <div class="flex gap-4">
                        <span class="text-xs uppercase tracking-widest text-gray-400 self-center">Compartilhar:</span>
                        <a href="#" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-brand-black hover:text-white hover:border-brand-black transition">LN</a>
                        <a href="#" class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-brand-black hover:text-white hover:border-brand-black transition">IG</a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-12">
                <div class="bg-gray-50 p-8 border border-gray-100 sticky top-32">
                    <h3 class="text-xl font-serif text-brand-black mb-8 pb-4 border-b border-gray-200">Mais do nosso Blog</h3>
                    
                    <div class="space-y-8">
                        
                        <a href="{{ route('blog.show') }}" class="group block">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-sm">
                                    <img src="{{ asset('img/DiogoMaia2.jpg') }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest text-brand-gold mb-1 block">Tendências</span>
                                    <h4 class="text-sm font-serif text-brand-black leading-snug group-hover:text-brand-gold transition">
                                        O Novo Perfil do Investidor de Luxo
                                    </h4>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('blog.show-intelligence') }}" class="group block">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-sm">
                                    <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?q=80&w=200&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest text-brand-gold mb-1 block">Inteligência</span>
                                    <h4 class="text-sm font-serif text-brand-black leading-snug group-hover:text-brand-gold transition">
                                        Inteligência de Mercado e Investimento
                                    </h4>
                                </div>
                            </div>
                        </a>

                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200">
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-4">Newsletter</h4>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">Receba insights exclusivos do mercado imobiliário premium.</p>
                        <form class="flex flex-col gap-2">
                            <input type="email" placeholder="Seu email" class="bg-white border border-gray-200 px-4 py-3 text-xs focus:outline-none focus:border-brand-gold transition">
                            <button class="bg-brand-black text-white text-xs font-bold uppercase tracking-widest py-3 hover:bg-brand-gold transition">Inscrever-se</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection