@extends('layouts.app')

@section('content')

<section class="relative h-[50vh] min-h-[400px] flex items-end justify-start bg-fixed bg-cover bg-center" 
         style="background-image: url('{{ asset('img/DiogoMaia2.jpg') }}');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-black/30"></div>
    
    <div class="relative z-10 container mx-auto px-6 pb-16" data-aos="fade-up">
        <div class="max-w-4xl">
            <div class="flex items-center gap-4 mb-6 text-white/80">
                <span class="uppercase tracking-[0.2em] text-xs border border-white/30 px-3 py-1">Tendências</span>
                <span class="text-xs uppercase tracking-widest">10 Dezembro, 2025</span>
            </div>
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-serif text-white leading-tight">
                O Novo Perfil do Investidor de Luxo: Discrição, Património e Propósito
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
                        "O mercado de luxo está a mudar. Já não se define apenas pelo preço, pelo tamanho do imóvel ou pelo prestígio da localização. O verdadeiro investidor de luxo em 2025 procura algo mais profundo: estabilidade, privacidade e um sentido de legado."
                    </p>

                    <p>
                        Essa transformação redefine o modo como os profissionais do setor atuam e como o investimento imobiliário de alto padrão é apresentado ao mercado.
                    </p>

                    <h2 class="mt-12 text-3xl">A transformação silenciosa do luxo</h2>
                    <p>
                        Durante anos, o luxo foi sinónimo de visibilidade. O imóvel de destaque, a fachada imponente e a exibição de conquistas marcavam o sucesso. Hoje, o cenário é diferente. O novo investidor quer discrição, segurança e propósito. Prefere a sofisticação silenciosa de uma moradia com arquitetura minimalista, rodeada de natureza, à ostentação de um palácio urbano.
                    </p>
                    <p>
                        Além disso, essa mudança reflete uma nova mentalidade: o luxo não é o que se mostra, mas o que se vive. A experiência, o conforto e a personalização tornaram-se mais valiosos do que qualquer logótipo.
                    </p>

                    <h2 class="mt-12 text-3xl">Do consumo à curadoria</h2>
                    <p>
                        O investidor de luxo atual não compra apenas um imóvel — constrói uma estratégia patrimonial. Antes de tomar uma decisão, ele analisa rentabilidade, liquidez e impacto social. Quer saber se a propriedade valoriza a longo prazo, se o estilo arquitetónico resiste ao tempo e se o investimento está alinhado com o seu modo de vida.
                    </p>
                    <p>
                        Assim, o consultor imobiliário precisa abandonar o papel de vendedor e assumir o papel de curador. Isso implica compreender o contexto de cada cliente, interpretar dados de mercado e apresentar soluções personalizadas. O investimento de luxo deixou de ser impulsivo; é hoje um processo pensado, racional e profundamente estratégico.
                    </p>

                    <h2 class="mt-12 text-3xl">Discrição como sinónimo de valor</h2>
                    <p>
                        Entre as características mais marcantes do novo investidor está a <strong>discrição</strong>. Em um mundo hiperconectado, onde tudo é partilhado, a privacidade tornou-se um bem escasso e altamente desejado.
                    </p>
                    <p>
                        As propriedades mais procuradas são aquelas que oferecem exclusividade e anonimato: moradias com acesso reservado, condomínios privados e localizações com baixa densidade populacional. A segurança, o silêncio e a confidencialidade passaram a ser elementos centrais da proposta de valor.
                    </p>

                    <h2 class="mt-12 text-3xl">O património como legado</h2>
                    <p>
                        O novo investidor de luxo encara o património como uma extensão da sua história. Não compra apenas para viver, mas para deixar algo significativo para as próximas gerações. O imóvel é visto como símbolo de continuidade, de raízes sólidas e de prosperidade sustentada.
                    </p>
                    <p>
                        Em vez de apostar em ganhos rápidos, o investidor procura valor estável, imóveis que mantenham relevância e que resistam às mudanças do mercado. O luxo, agora, está no detalhe e na intenção.
                    </p>

                    <h2 class="mt-12 text-3xl">Propósito e sustentabilidade</h2>
                    <p>
                        A responsabilidade ambiental e social deixou de ser opcional — tornou-se critério de decisão. Os investidores valorizam construtoras sustentáveis, tecnologias de eficiência energética e projetos que respeitam o território. A união entre propósito e rentabilidade cria um novo paradigma: o luxo consciente.
                    </p>

                    <h2 class="mt-12 text-3xl">O futuro do luxo é silencioso, inteligente e humano</h2>
                    <p>
                        O novo investidor de luxo é discreto, informado e orientado por propósito. Busca imóveis que traduzam valor real e estabilidade, e profissionais que ofereçam visão e integridade. Para o mercado, essa mudança representa uma oportunidade extraordinária. Significa elevar o nível da comunicação, refinar o atendimento e alinhar cada transação com o que realmente importa: o legado que se constrói através do património.
                    </p>
                    <p class="font-medium text-brand-black mt-8">
                        E aqueles que compreenderem essa mudança estarão à frente do tempo.
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
                        
                        <a href="{{ route('blog.show-intelligence') }}" class="group block">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-sm">
                                    <img src="https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest text-brand-gold mb-1 block">Inteligência</span>
                                    <h4 class="text-sm font-serif text-brand-black leading-snug group-hover:text-brand-gold transition">
                                        Como a Inteligência de Mercado Redefine o Investimento
                                    </h4>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('blog.show-locations') }}" class="group block">
                            <div class="flex gap-4">
                                <div class="w-20 h-20 flex-shrink-0 overflow-hidden rounded-sm">
                                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest text-brand-gold mb-1 block">Localização</span>
                                    <h4 class="text-sm font-serif text-brand-black leading-snug group-hover:text-brand-gold transition">
                                        Lisboa, Cascais e Algarve: Os Três Eixos de Valor
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