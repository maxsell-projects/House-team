@extends('layouts.app')

@section('content')

{{-- DADOS DA EQUIPA (CONFIGURAÇÃO) --}}
@php
    $leader = [
        'name' => 'Hugo Gaito',
        'role' => 'Broker Empreendedor',
        'photo' => 'Hugo.png',
        'phone' => null,
        'email' => null,
        'bio' => null
    ];

    $team = [
        [
            'name' => 'Carlos Pinto',
            'role' => 'Consultor Imobiliário',
            'photo' => 'carlos.png',
            'phone' => '+351917204561',
            'email' => null,
            'bio' => ''
        ],
        [
            'name' => 'Mariana Faria',
            'role' => 'Consultora Imobiliária',
            'photo' => 'mariana.png',
            'phone' => '+351961222024',
            'email' => null,
            'bio' => ''
        ],
        [
            'name' => 'Leonor Tudela',
            'role' => 'Consultora Imobiliária',
            'photo' => 'leonor.png',
            'phone' => '+351962501264',
            'email' => null,
            'bio' => 'Sou licenciada em Gestão de Marketing, com 25 anos de experiência profissional, sempre focada no cuidado ao outro. Sou uma pessoa alegre, dedicada, humilde, responsável e cumpridora, que gosta de novos desafios, que se preocupa com o outro e, sobretudo, que gosta de se relacionar…'
        ],
        [
            'name' => 'Anabela Inácio',
            'role' => 'Consultora Imobiliária',
            'photo' => 'anabela.png',
            'phone' => '+351964872394',
            'email' => null,
            'bio' => 'O meu nome é Anabela Inácio, fui bancária durante 26 anos. Sempre dedicada à área comercial, passando do financiamento automóvel a Gestora de conta de Clientes, considero o ramo Imobiliário um valor acrescentado a toda a minha relação com pessoas. Procuro estar sempre bem informada…'
        ],
        [
            'name' => 'Sandra Guedes',
            'role' => 'Consultora Imobiliária',
            'photo' => 'sandra.png',
            'phone' => '+351934188303',
            'email' => null,
            'bio' => 'O meu nome é Sandra Guedes, tenho 50 anos e sou uma apaixonada por “pessoas” e por “casas”, pelos sonhos e projetos envolvidos, pelas histórias nelas vividas… Sou licenciada em Gestão de Recursos Humanos, com mais de 25 anos de experiência profissional, a lidar com…'
        ],
        [
            'name' => 'Sofia Leitão',
            'role' => 'Consultora Imobiliária',
            'photo' => 'sofia.png',
            'phone' => '+351917715544',
            'email' => 'sofialeitao@remax.pt',
            'bio' => 'O meu nome é Sofia, tenho 52 anos e para mim o imobiliário é a forma que encontrei de satisfazer um interesse pessoal e ao mesmo tempo realizar os sonhos de outras pessoas. Morei fora de Portugal durante 17 anos, e durante esse período era…'
        ],
        [
            'name' => 'Inês Lobo',
            'role' => 'Consultora Imobiliária',
            'photo' => 'ines.png',
            'phone' => '+351913163655',
            'email' => 'inesamaral@remax.pt',
            'bio' => 'O meu nome é Inês Lobo, tenho 28 anos e sou consultora imobiliária. Licenciei-me em Psicologia, mas sempre tive uma grande paixão pelo ramo imobiliário, por isso escolhi a RE/MAX para trabalhar. A par com a Equipa House Team consultores, integro a RE/MAX – ExpoGroup,…'
        ],
        [
            'name' => 'Matilde Pereira',
            'role' => 'Consultora Imobiliária',
            'photo' => 'matilde.png',
            'phone' => '+351967823022',
            'email' => 'matildepereira@remax.pt',
            'bio' => 'O meu nome é Matilde Pereira. Desde que me conheço como pessoa que estou no mundo do empreendedorismo! Tenho um gosto especial por relações com pessoas e por fazer parte da vida dos meus clientes. Acredito que para se ser grande tem de se sonhar…'
        ],
        [
            'name' => 'Marília Miranda',
            'role' => 'Consultora Imobiliária',
            'photo' => 'marilia.png',
            'phone' => '+351910970808',
            'email' => 'mmiranda@remax.pt',
            'bio' => ''
        ],
        [
            'name' => 'Margarida Lopes',
            'role' => 'Consultora Imobiliária',
            'photo' => 'margarida.png',
            'phone' => '+351967635312',
            'email' => null,
            'bio' => 'Desde 2008 a trabalhar no Ramo Imobiliário, tendo me iniciado na área da Consultoria Financeira e, mais tarde, em 2014, encontrado a minha vocação comercial como Consultora Imobiliária. Desde essa data tenho sido reconhecida e premiada todos os anos pelo trabalho e volume de negócios…'
        ],
        [
            'name' => 'Marina Machado',
            'role' => 'Assistente',
            'photo' => 'marina.png',
            'phone' => '+351916123562',
            'email' => null,
            'bio' => 'O seu percurso profissional por áreas de trabalho muito distintas, formação na área da saúde e 5 anos de experiência no ramo imobiliário, determinou a sua elevada capacidade de adaptação e experiência no contacto com pessoas. No ramo imobiliário foi consultora mas é a componente…'
        ],
        [
            'name' => 'Pedro Santos',
            'role' => 'Consultor Imobiliário',
            'photo' => 'pedro.png',
            'phone' => '+351917827196',
            'email' => null,
            'bio' => 'Desde sempre ligado à área comercial, abracei este projeto em 2015. Pode contar com o meu profissionalismo e dedicação.'
        ],
        [
            'name' => 'Hugo Carvalho',
            'role' => 'Consultor Imobiliário',
            'photo' => 'hugo2.png',
            'phone' => '+351961407430',
            'email' => null,
            'bio' => 'Possuo um forte interesse pela área imobiliária, visto que gosto de casas e das suas ínfimas particularidades. Para mim uma “casa” não se trata apenas de algo físico, mas sim de um conceito construtivo, das vivências associadas e memórias que se constroem. É com base…'
        ],
        [
            'name' => 'David Simões',
            'role' => 'Consultor Imobiliário',
            'photo' => 'david.png',
            'phone' => '+351961044596',
            'email' => null,
            'bio' => 'Com vasta experiência na área comercial e no ramo imobiliário, conte com o meu profissionalismo'
        ],
    ];
@endphp

{{-- COMPONENTE ALPINE PARA O MODAL --}}
<div x-data="{ 
    activeMember: null, 
    openModal: false,
    showMember(member) {
        this.activeMember = member;
        this.openModal = true;
        document.body.style.overflow = 'hidden'; // Bloqueia scroll
    },
    closeModal() {
        this.openModal = false;
        setTimeout(() => this.activeMember = null, 300);
        document.body.style.overflow = 'auto'; // Libera scroll
    }
}">

    <section class="bg-ht-navy pt-40 pb-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
        <div class="container mx-auto px-6 relative z-10">
            <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">A Nossa Força</p>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Conheça a Equipa</h1>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg font-light">
                Profissionais dedicados, apaixonados pelo imobiliário e focados em realizar os seus sonhos.
            </p>
        </div>
    </section>

    <section class="py-20 bg-white relative">
        <div class="container mx-auto px-6 text-center">
            <div class="inline-block relative group cursor-default" data-aos="zoom-in">
                <div class="absolute -inset-4 bg-gradient-to-r from-ht-navy via-ht-accent to-ht-navy rounded-full opacity-20 blur-xl group-hover:opacity-40 transition duration-700"></div>
                
                <div class="relative w-48 h-48 md:w-64 md:h-64 mx-auto rounded-full p-2 bg-white shadow-2xl overflow-hidden border-4 border-slate-50">
                    <img src="{{ asset('img/team/' . $leader['photo']) }}" 
                         alt="{{ $leader['name'] }}" 
                         class="w-full h-full object-cover rounded-full transform group-hover:scale-105 transition duration-700">
                </div>
                
                <h2 class="text-3xl font-black text-ht-navy mt-8">{{ $leader['name'] }}</h2>
                <p class="text-ht-accent font-bold uppercase tracking-widest text-sm mt-2">{{ $leader['role'] }}</p>
            </div>
        </div>
    </section>

    <section class="py-16 bg-slate-50 border-t border-slate-200">
        <div class="container mx-auto px-6 md:px-12">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
                @foreach($team as $member)
                    <div class="bg-white rounded-3xl p-8 text-center shadow-sm hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 cursor-pointer border border-slate-100 group relative overflow-hidden"
                         @click="showMember({{ json_encode($member) }})"
                         data-aos="fade-up">
                        
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-ht-navy to-ht-accent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>

                        <div class="w-32 h-32 mx-auto rounded-full p-1 border-2 border-slate-100 group-hover:border-ht-accent transition-colors duration-300 mb-6 relative">
                            <img src="{{ asset('img/team/' . $member['photo']) }}" 
                                 class="w-full h-full object-cover rounded-full grayscale group-hover:grayscale-0 transition duration-500">
                        </div>

                        <h3 class="text-lg font-bold text-ht-navy mb-1 group-hover:text-ht-accent transition-colors">{{ $member['name'] }}</h3>
                        <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-6">{{ $member['role'] }}</p>

                        <button class="text-xs font-bold text-ht-navy border-b-2 border-ht-navy/10 pb-1 group-hover:border-ht-accent group-hover:text-ht-accent transition-all">
                            Ver Perfil
                        </button>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <div x-show="openModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="absolute inset-0 bg-ht-navy/80 backdrop-blur-md transition-opacity duration-300"
             x-show="openModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        <div class="relative bg-white w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row transform transition-all duration-300"
             x-show="openModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-10">
            
            <button @click="closeModal()" class="absolute top-4 right-4 z-50 p-2 bg-white/20 backdrop-blur rounded-full text-slate-800 md:text-white hover:bg-white/40 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="md:w-2/5 bg-slate-100 relative h-64 md:h-auto">
                <template x-if="activeMember">
                    <img :src="'{{ asset('img/team/') }}/' + activeMember.photo" 
                         class="w-full h-full object-cover absolute inset-0">
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/80 to-transparent md:bg-gradient-to-r"></div>
            </div>

            <div class="md:w-3/5 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                <template x-if="activeMember">
                    <div>
                        <span class="inline-block px-3 py-1 bg-blue-50 text-ht-accent text-[10px] font-bold uppercase tracking-widest rounded-full mb-4" x-text="activeMember.role"></span>
                        
                        <h2 class="text-3xl md:text-4xl font-black text-ht-navy mb-6" x-text="activeMember.name"></h2>
                        
                        <div x-show="activeMember.bio" class="mb-8">
                            <p class="text-slate-500 leading-relaxed text-sm md:text-base font-medium border-l-4 border-ht-accent pl-4" x-text="activeMember.bio"></p>
                        </div>
                        <div x-show="!activeMember.bio" class="mb-8">
                            <p class="text-slate-400 italic text-sm">Contacte-me para agendar uma visita ou discutir o seu imóvel.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a :href="'https://wa.me/' + (activeMember.phone ? activeMember.phone.replace(/[^0-9]/g, '') : '')" 
                               target="_blank"
                               class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-xl font-bold uppercase text-xs tracking-widest transition-all shadow-lg hover:shadow-green-500/30">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                WhatsApp
                            </a>

                            <a :href="'mailto:' + activeMember.email" 
                               x-show="activeMember.email"
                               class="flex items-center justify-center gap-2 bg-slate-100 hover:bg-ht-navy hover:text-white text-ht-navy py-3 px-6 rounded-xl font-bold uppercase text-xs tracking-widest transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Enviar Email
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

@endsection