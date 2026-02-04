@extends('layouts.app')

@section('content')

{{-- 
    1. OVERRIDE DE DESIGN SYSTEM (SE TIVER CONSULTORA)
--}}
@if(isset($consultant))
    <style>
        /* Fontes Premium */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap');
        
        :root {
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --color-gold: #c5a059;
            --color-navy: #1e293b;
        }

        /* Override de Fontes */
        body { font-family: var(--font-sans) !important; }
        h1, h2, h3, h4 { font-family: var(--font-serif) !important; }

        /* Override de Cores */
        .bg-ht-accent { background-color: var(--color-gold) !important; }
        .text-ht-accent { color: var(--color-gold) !important; }
        .border-ht-accent { border-color: var(--color-gold) !important; }
        .ring-ht-accent { --tw-ring-color: var(--color-gold) !important; }
        .focus\:ring-ht-accent:focus { --tw-ring-color: var(--color-gold) !important; }
        
        .bg-ht-navy { background-color: var(--color-navy) !important; }
        .text-ht-navy { color: var(--color-navy) !important; }
        
        /* Ajustes de Hover */
        .hover\:bg-ht-accent:hover { background-color: #b08d4b !important; }
        
        /* Ajuste visual dos inputs (mais elegantes) */
        input[type="number"], select {
            border-radius: 4px !important;
            border-color: #e2e8f0;
        }
        input[type="number"]:focus, select:focus {
            border-color: var(--color-gold) !important;
            box-shadow: 0 0 0 1px var(--color-gold) !important;
        }
        
        /* Ajuste do botão principal */
        button.bg-ht-navy {
            background-color: var(--color-navy) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
            border-radius: 4px !important;
        }
        button.bg-ht-navy:hover {
            background-color: var(--color-gold) !important;
            transform: translateY(-2px);
        }
    </style>
@endif

{{-- HEADER --}}
<div class="bg-ht-navy text-white pt-40 pb-20 text-center relative overflow-hidden">
    @if(isset($consultant))
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        <div class="absolute top-0 right-0 w-1/3 h-full bg-ht-accent opacity-10" style="clip-path: polygon(20% 0%, 100% 0, 100% 100%, 0% 100%);"></div>
    @else
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    @endif

    <div class="container mx-auto px-6 relative z-10" data-aos="fade-down">
        @if(isset($consultant))
             <span class="text-ht-accent font-bold tracking-widest text-xs uppercase mb-2 block">{{ __('menu.tools') }}</span>
        @endif
        <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-2">{{ __('tools.imt.title') }}</h1>
        <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">{{ __('tools.imt.subtitle') }}</p>
    </div>
</div>

<section class="py-16 bg-slate-50 relative overflow-hidden" x-data="imtCalculator()" x-init="calculate()">
    
    @if(isset($consultant))
        <div class="absolute top-0 left-0 w-1/3 h-96 bg-ht-navy opacity-5 -z-10" style="clip-path: polygon(0 0, 50% 0, 100% 100%, 0% 100%);"></div>
    @endif

    <div class="container mx-auto px-4 md:px-8 max-w-6xl relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- ÁREA DO FORMULÁRIO --}}
            <div class="lg:col-span-7 space-y-8" data-aos="fade-up">
                
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                        {{ __('tools.imt.section_data') }}
                    </h3>
                    
                    <div class="space-y-6">
                        
                        {{-- Local do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-3">{{ __('tools.imt.label_location') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="location" value="continente" x-model="location" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-sm font-bold text-slate-600 peer-checked:text-ht-navy text-center group-hover:border-ht-navy/30">
                                        {{ __('tools.imt.loc_continente') }}
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="location" value="ilhas" x-model="location" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-sm font-bold text-slate-600 peer-checked:text-ht-navy text-center group-hover:border-ht-navy/30">
                                        {{ __('tools.imt.loc_islands') }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Finalidade do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.imt.label_purpose') }}</label>
                            <div class="relative">
                                <select x-model="purpose" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent text-ht-navy font-medium appearance-none">
                                    <option value="hpp">{{ __('tools.imt.purpose_hpp') }}</option>
                                    <option value="secundaria">{{ __('tools.imt.purpose_secondary') }}</option>
                                    <option value="rustico">{{ __('tools.imt.purpose_rustic') }}</option>
                                    <option value="urbano">{{ __('tools.imt.purpose_urban') }}</option>
                                    <option value="offshore_pessoal">{{ __('tools.imt.purpose_offshore_personal') }}</option>
                                    <option value="offshore_entidade">{{ __('tools.imt.purpose_offshore_entity') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Preço do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.imt.label_price') }}</label>
                            <div class="relative">
                                <input type="number" x-model.number="propertyValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent text-ht-navy font-bold placeholder-slate-400" placeholder="0,00">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">€</span>
                            </div>
                        </div>

                        {{-- Número de compradores --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-3">{{ __('tools.imt.label_buyers') }}</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer flex-1 group">
                                    <input type="radio" name="buyers" :value="1" x-model.number="buyersCount" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy group-hover:border-ht-navy/30">
                                        1
                                    </div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="buyers" :value="2" x-model.number="buyersCount" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy group-hover:border-ht-navy/30">
                                        2
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Informação Compradores --}}
                <div x-transition class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        {{ __('tools.imt.section_buyers_info') }}
                    </h3>
                    
                    <div class="space-y-6">
                        
                        {{-- Comprador 1 --}}
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 block">{{ __('tools.imt.buyer_1') }}</span>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-ht-navy mb-2">{{ __('tools.imt.label_age') }}</label>
                                    <input type="number" x-model.number="buyer1Age" @input="checkAge(1); calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent" placeholder="Ex: 30">
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-1 mb-2">
                                        <label class="block text-xs font-bold text-ht-navy">{{ __('tools.imt.label_eligible') }}</label>
                                        <div class="group relative cursor-help">
                                            <span class="bg-slate-200 text-slate-500 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold">?</span>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-ht-navy text-white text-xs p-2 rounded hidden group-hover:block z-20 text-center shadow-lg">
                                                {{ __('tools.imt.tooltip_eligible') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button type="button" @click="setBuyerEligible(1, true)" :class="buyer1Eligible ? 'bg-ht-navy text-white border-ht-navy' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">{{ __('tools.imt.yes') }}</button>
                                        <button type="button" @click="setBuyerEligible(1, false)" :class="!buyer1Eligible ? 'bg-slate-200 text-slate-700 border-slate-300' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">{{ __('tools.imt.no') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Comprador 2 (Condicional) --}}
                        <div x-show="buyersCount === 2" x-transition class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 block">{{ __('tools.imt.buyer_2') }}</span>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-ht-navy mb-2">{{ __('tools.imt.label_age') }}</label>
                                    <input type="number" x-model.number="buyer2Age" @input="checkAge(2); calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent" placeholder="Ex: 36">
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-1 mb-2">
                                        <label class="block text-xs font-bold text-ht-navy">{{ __('tools.imt.label_eligible') }}</label>
                                        <div class="group relative cursor-help">
                                            <span class="bg-slate-200 text-slate-500 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold">?</span>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-ht-navy text-white text-xs p-2 rounded hidden group-hover:block z-20 text-center shadow-lg">
                                                {{ __('tools.imt.tooltip_eligible') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button type="button" @click="setBuyerEligible(2, true)" :class="buyer2Eligible ? 'bg-ht-navy text-white border-ht-navy' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">{{ __('tools.imt.yes') }}</button>
                                        <button type="button" @click="setBuyerEligible(2, false)" :class="!buyer2Eligible ? 'bg-slate-200 text-slate-700 border-slate-300' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">{{ __('tools.imt.no') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Botão Simular (Mobile) --}}
                <div class="mt-8 lg:hidden">
                    <button @click="scrollToResults" class="w-full bg-ht-navy text-white font-black uppercase tracking-widest py-4 rounded-xl shadow-lg hover:bg-ht-primary transition-all">
                        {{ __('tools.imt.btn_see_results') }}
                    </button>
                </div>

            </div>

            {{-- ÁREA DE RESULTADOS --}}
            <div class="lg:col-span-5" id="results-area" data-aos="fade-left">
                <div class="sticky top-32 space-y-6">
                    
                    {{-- Cartão Principal --}}
                    <div class="bg-ht-navy rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                        {{-- Efeito Visual --}}
                        <div class="absolute top-0 right-0 p-6 opacity-10">
                            <svg class="w-32 h-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>

                        <div class="flex justify-between items-start mb-6 border-b border-white/10 pb-4 relative z-10">
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('tools.imt.results_title') }}</h3>
                            
                            {{-- Botão de Transparência do Cálculo --}}
                            <button @click="showBreakdown = !showBreakdown" class="text-xs font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1 uppercase">
                                <svg x-show="!showBreakdown" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 12h.01"/></svg>
                                <svg x-show="showBreakdown" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span x-text="showBreakdown ? '{{ __('tools.imt.btn_close_details') }}' : '{{ __('tools.imt.btn_open_details') }}'"></span>
                            </button>
                        </div>

                        {{-- Seção de Detalhes do Cálculo --}}
                        <div x-show="showBreakdown" x-transition:enter.duration.300ms x-transition:leave.duration.300ms class="bg-white/5 p-4 mb-6 rounded-2xl border border-white/10 text-xs relative z-10">
                            <h4 class="font-bold mb-3 text-ht-accent uppercase tracking-wider">{{ __('tools.imt.breakdown_title') }}</h4>
                            
                            <div class="space-y-1 text-slate-300">
                                <div class="flex justify-between">
                                    <span>{{ __('tools.imt.breakdown_taxable') }}</span>
                                    <span class="font-bold">€ <span x-text="formatMoney(imtBreakdown.taxableValue)"></span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __('tools.imt.breakdown_buyers') }}</span>
                                    <span class="font-bold" x-text="buyersCount"></span>
                                </div>
                            </div>

                            <div class="space-y-1 text-slate-300 mt-4 border-t border-white/10 pt-3">
                                <p class="font-bold text-sm text-white mb-2" x-text="'{{ __('tools.imt.breakdown_rate_applied') }}: ' + imtBreakdown.rateText"></p>
                                
                                <template x-if="imtBreakdown.isJovemBenefit && imtBreakdown.isMarginal">
                                    <p class="text-slate-400">{{ __('tools.imt.breakdown_marginal_note') }} <span x-text="formatMoney(imtBreakdown.marginalExemption) + '€'"></span>.</p>
                                </template>
                                
                                <div x-show="imtBreakdown.rateText.includes('Progressiva')" class="text-slate-400">
                                    {{ __('tools.imt.breakdown_formula') }}
                                </div>
                            </div>

                            <div class="flex justify-between text-white border-t border-white/10 pt-3 mt-3">
                                <span class="font-bold">{{ __('tools.imt.breakdown_total') }}</span>
                                <span class="font-black text-ht-accent">€ <span x-text="formatMoney(imtBreakdown.finalIMT)"></span></span>
                            </div>
                        </div>
                        
                        {{-- Resultados Principais --}}
                        <div class="space-y-5 relative z-10">
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-400">{{ __('tools.imt.result_imt') }}</span>
                                <span class="font-bold text-lg">€ <span x-text="formatMoney(finalIMT)"></span></span>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm font-medium border-b border-white/10 pb-5">
                                <span class="text-slate-400">{{ __('tools.imt.result_stamp') }}</span>
                                <span class="font-bold text-lg">€ <span x-text="formatMoney(finalStamp)"></span></span>
                            </div>

                            <div class="bg-white/10 p-6 rounded-2xl border border-white/5 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-widest text-ht-accent font-bold mb-2">{{ __('tools.imt.result_total') }}</p>
                                <p class="text-4xl font-black text-white tracking-tighter">€ <span x-text="formatMoney(totalPayable)"></span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Call to Action --}}
                    <div class="text-center space-y-4 pt-4">
                        <p class="text-sm font-medium text-slate-600">{{ __('tools.imt.cta_text') }}</p>
                        <button @click="showLeadModal = true" class="block w-full bg-ht-accent text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-red-700 transition-all text-xs shadow-lg transform hover:-translate-y-1">
                            {{ __('tools.imt.btn_report') }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE LEAD --}}
    <div x-show="showLeadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showLeadModal" class="fixed inset-0 bg-ht-navy/80 backdrop-blur-sm transition-opacity" @click="showLeadModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="px-8 pt-8 pb-6">
                    <h3 class="text-2xl font-black text-ht-navy mb-2 text-center">{{ __('tools.imt.modal_title') }}</h3>
                    <p class="text-sm text-slate-500 mb-6 text-center">{{ __('tools.imt.modal_subtitle') }}</p>
                    <div class="space-y-4">
                        <input type="text" x-model="lead_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent" placeholder="{{ __('tools.imt.input_name') }}">
                        <input type="email" x-model="lead_email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent" placeholder="{{ __('tools.imt.input_email') }}">
                        {{-- NOVO CAMPO DE TELEFONE --}}
                        <input type="tel" x-model="lead_phone" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent" placeholder="{{ __('contact.placeholder_phone') }} (Obrigatório)">
                    </div>
                </div>
                <div class="bg-slate-50 px-8 py-6 flex flex-col gap-3">
                    <button type="button" @click="submitLead" class="w-full bg-ht-accent text-white font-bold py-3 rounded-xl hover:bg-red-700 transition-all" :disabled="loading">
                        <span x-show="!loading">{{ __('tools.imt.btn_submit') }}</span>
                        <span x-show="loading">{{ __('tools.imt.btn_sending') }}</span>
                    </button>
                    <button @click="showLeadModal = false" class="text-xs text-slate-400 font-bold uppercase hover:text-ht-navy">{{ __('tools.imt.btn_cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    function imtCalculator() {
        return {
            // --- DADOS ---
            location: 'continente',
            purpose: 'hpp',
            propertyValue: 250000,
            buyersCount: 1,
            
            buyer1Age: 30, 
            buyer1Eligible: true,
            
            buyer2Age: '',
            buyer2Eligible: false,

            finalIMT: 0,
            finalStamp: 0,
            totalPayable: 0,
            
            // Variáveis de Transparência
            imtBreakdown: {
                taxableValue: 0,
                rateText: 'N/A',
                abatement: 0,
                finalIMT: 0,
                isJovemBenefit: false,
                isMarginal: false,
                marginalExemption: 0,
                marginalRate: 0,
            },
            showBreakdown: false,

            // Variáveis do Modal de Lead
            showLeadModal: false,
            loading: false,
            lead_name: '',
            lead_email: '',
            lead_phone: '',

            // --- MÉTODOS DE UI ---
            setBuyerEligible(buyerIndex, value) {
                if (buyerIndex === 1) this.buyer1Eligible = value;
                if (buyerIndex === 2) this.buyer2Eligible = value;
                this.calculate();
            },

            formatMoney(value) {
                return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            },

            scrollToResults() {
                const el = document.getElementById('results-area');
                if(el) el.scrollIntoView({ behavior: 'smooth' });
                this.calculate();
            },

            checkAge(buyerIndex) {
                // Validação visual simples, a lógica real está no calculate()
                if (buyerIndex === 1 && this.buyer1Age > 35) this.buyer1Eligible = false;
                if (buyerIndex === 2 && this.buyer2Age > 35) this.buyer2Eligible = false;
            },

            // --- LÓGICA DE CÁLCULO ATUALIZADA (OE 2026) ---
            calculateNormalIMT(valor, tabela) {
                let taxa = 0;
                let parcelaAbater = 0;
                
                // TABELA I (Continente - HPP)
                if (tabela === 'hpp_continente') {
                    if (valor <= 106346) { taxa = 0; parcelaAbater = 0; }
                    else if (valor <= 145470) { taxa = 0.02; parcelaAbater = 2126.92; }
                    else if (valor <= 198347) { taxa = 0.05; parcelaAbater = 6491.02; }
                    else if (valor <= 330539) { taxa = 0.07; parcelaAbater = 10457.96; }
                    else if (valor <= 660982) { taxa = 0.08; parcelaAbater = 13763.35; } // Limite superior da progressiva
                    else if (valor <= 1150853) { return valor * 0.06; } // Taxa Única 6%
                    else { return valor * 0.075; } // Taxa Única 7.5%
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // TABELA IV (Ilhas - HPP)
                if (tabela === 'hpp_ilhas') {
                    if (valor <= 132933) { taxa = 0; parcelaAbater = 0; }
                    else if (valor <= 181838) { taxa = 0.02; parcelaAbater = 2658.66; }
                    else if (valor <= 247934) { taxa = 0.05; parcelaAbater = 8113.80; }
                    else if (valor <= 413174) { taxa = 0.07; parcelaAbater = 13072.48; }
                    else if (valor <= 826228) { taxa = 0.08; parcelaAbater = 17204.22; } // Limite superior da progressiva
                    else if (valor <= 1438566) { return valor * 0.06; } // Taxa Única 6%
                    else { return valor * 0.075; } // Taxa Única 7.5%
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // TABELA III (Continente - Secundária/Habitação)
                if (tabela === 'secundaria_continente') {
                    if (valor <= 106346) { taxa = 0.01; parcelaAbater = 0; }
                    else if (valor <= 145470) { taxa = 0.02; parcelaAbater = 1063.46; }
                    else if (valor <= 198347) { taxa = 0.05; parcelaAbater = 5427.56; }
                    else if (valor <= 330539) { taxa = 0.07; parcelaAbater = 9394.50; }
                    else if (valor <= 633931) { taxa = 0.08; parcelaAbater = 12699.89; } // Nota: Limite ligeiramente diferente da HPP
                    else if (valor <= 1150853) { return valor * 0.06; } // Taxa Única 6%
                    else { return valor * 0.075; } // Taxa Única 7.5%
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // TABELA VI (Ilhas - Secundária/Habitação)
                if (tabela === 'secundaria_ilhas') {
                    if (valor <= 132933) { taxa = 0.01; parcelaAbater = 0; }
                    else if (valor <= 181838) { taxa = 0.02; parcelaAbater = 1329.33; }
                    else if (valor <= 247934) { taxa = 0.05; parcelaAbater = 6784.47; }
                    else if (valor <= 413174) { taxa = 0.07; parcelaAbater = 11743.15; }
                    else if (valor <= 792414) { taxa = 0.08; parcelaAbater = 15874.89; }
                    else if (valor <= 1438566) { return valor * 0.06; } // Taxa Única 6%
                    else { return valor * 0.075; } // Taxa Única 7.5%
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }
                
                return 0;
            },

            // Lógica IMT Jovem 2026 (Tabelas II e V)
            calculateYoungIMT(valor, location) {
                // Limites 2026
                const limitIsencao = location === 'continente' ? 330539 : 413174;
                const limitParcial = location === 'continente' ? 660982 : 826228;
                const taxaExcedente = 0.08;

                if (valor <= limitIsencao) {
                    // Isenção total até ao 1º escalão
                    return 0; 
                } else if (valor <= limitParcial) {
                    // Paga 8% apenas sobre o valor que excede a isenção
                    return (valor - limitIsencao) * taxaExcedente;
                } else {
                    // Se passar o limite parcial, perde o benefício jovem e aplica a tabela normal (taxas únicas de 6% ou 7.5%)
                    const tabela = location === 'continente' ? 'hpp_continente' : 'hpp_ilhas';
                    return this.calculateNormalIMT(valor, tabela);
                }
            },

            calculate() {
                let valorTotal = this.propertyValue || 0;
                
                if (valorTotal <= 0) {
                    this.finalIMT = 0;
                    this.finalStamp = 0;
                    this.totalPayable = 0;
                    this.imtBreakdown = { taxableValue: 0, rateText: 'N/A', abatement: 0, finalIMT: 0, isJovemBenefit: false, isMarginal: false, marginalExemption: 0, marginalRate: 0 };
                    return;
                }

                let imtBaseNormal = 0;
                let rateSelo = 0.008; 
                let isHPP = this.purpose === 'hpp';
                let isContinente = this.location === 'continente';
                let imtBreakdownText = '';

                // 1. Calcular o IMT Base "Normal" (sem considerar jovem ainda)
                if (this.purpose === 'rustico') {
                    imtBaseNormal = valorTotal * 0.05;
                    imtBreakdownText = '5% (Rústico)';
                } else if (this.purpose === 'urbano') {
                    imtBaseNormal = valorTotal * 0.065;
                    imtBreakdownText = '6.5% (Outros Urbanos)';
                } else if (this.purpose === 'offshore_pessoal' || this.purpose === 'offshore_entidade') {
                    imtBaseNormal = valorTotal * 0.10;
                    imtBreakdownText = '10% (Paraíso Fiscal)';
                } else {
                    let tabela = isHPP ? 
                                (isContinente ? 'hpp_continente' : 'hpp_ilhas') : 
                                (isContinente ? 'secundaria_continente' : 'secundaria_ilhas');
                    
                    imtBaseNormal = this.calculateNormalIMT(valorTotal, tabela);
                    imtBreakdownText = isHPP ? 'Tabela HPP (2026)' : 'Tabela Secundária (2026)';
                }

                let imtBaseJovem = imtBaseNormal; // Por defeito, assume normal
                let seloBaseJovem = valorTotal * rateSelo;
                let isJovemBenefitApplied = false;
                
                // 2. Verificar Elegibilidade Jovem
                // Só se aplica se for HPP
                const isBuyer1Eligible = this.buyer1Eligible && this.buyer1Age <= 35;
                const isBuyer2Eligible = this.buyersCount === 2 && this.buyer2Eligible && this.buyer2Age <= 35;
                const anyYoungBuyer = isBuyer1Eligible || isBuyer2Eligible;

                if (isHPP && anyYoungBuyer) {
                    isJovemBenefitApplied = true;
                    const limitIsencao = isContinente ? 330539 : 413174;
                    const limitParcial = isContinente ? 660982 : 826228;
                    
                    // Cálculo da base "Jovem" (valor total do imóvel)
                    if (valorTotal <= limitParcial) {
                        imtBaseJovem = this.calculateYoungIMT(valorTotal, this.location);
                        
                        // Lógica de Imposto de Selo Jovem
                        if (valorTotal <= limitIsencao) {
                            seloBaseJovem = 0; // Isenção total de selo
                            imtBreakdownText = 'Isenção Total Jovem (OE2026)';
                            this.imtBreakdown.isMarginal = false;
                            this.imtBreakdown.marginalExemption = valorTotal;
                        } else {
                            // Paga selo sobre o excedente
                            seloBaseJovem = (valorTotal - limitIsencao) * 0.008;
                            imtBreakdownText = 'Jovem: Isenção Parc. + 8% Excedente';
                            this.imtBreakdown.isMarginal = true;
                            this.imtBreakdown.marginalExemption = limitIsencao;
                            this.imtBreakdown.marginalRate = 8;
                        }
                    } else {
                        // Se ultrapassar o limite parcial (660k/826k), perde isenção jovem
                         imtBreakdownText = 'Taxa Normal (Acima teto Jovem)';
                         // Reverte para o cálculo normal, pois perde o benefício
                         imtBaseJovem = imtBaseNormal; 
                         seloBaseJovem = valorTotal * rateSelo;
                    }
                }
                
                this.imtBreakdown.isJovemBenefit = isJovemBenefitApplied;

                // 3. Distribuir quotas (Splitting)
                let buyers = this.buyersCount;
                let finalIMT = 0;
                let finalStamp = 0;

                for (let i = 1; i <= buyers; i++) {
                    let isEligible = false;
                    if (this.purpose === 'hpp') {
                        if (i === 1 && isBuyer1Eligible) isEligible = true;
                        if (i === 2 && isBuyer2Eligible) isEligible = true;
                    }

                    // Se o comprador for elegível E o valor permitir benefício, usa a base jovem dividida
                    // Caso contrário, usa a base normal dividida
                    if (isEligible && valorTotal <= (isContinente ? 660982 : 826228)) {
                        finalIMT += (imtBaseJovem / buyers);
                        finalStamp += (seloBaseJovem / buyers);
                    } else {
                        finalIMT += (imtBaseNormal / buyers);
                        finalStamp += ((valorTotal * rateSelo) / buyers);
                    }
                }
                
                this.finalIMT = finalIMT;
                this.finalStamp = finalStamp;
                this.totalPayable = finalIMT + finalStamp;

                this.imtBreakdown.rateText = imtBreakdownText;
                this.imtBreakdown.taxableValue = valorTotal;
                this.imtBreakdown.finalIMT = finalIMT;
            },

            // --- LÓGICA DE ENVIO DE LEAD (Mantida) ---
            async submitLead() {
                if(!this.lead_name || !this.lead_email || !this.lead_phone) { 
                    alert('{{ __('tools.imt.alert_fill') }}'); 
                    return; 
                }
                this.loading = true;
                
                try {
                    const response = await fetch('{{ url("/ferramentas/imt/enviar") }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            propertyValue: this.propertyValue,
                            location: this.location,
                            purpose: this.purpose,
                            finalIMT: this.finalIMT,
                            finalStamp: this.finalStamp,
                            totalPayable: this.totalPayable,
                            lead_name: this.lead_name,
                            lead_email: this.lead_email,
                            lead_phone: this.lead_phone
                        })
                    });

                    if (!response.ok) throw new Error('Falha no envio');

                    alert('{{ __('tools.imt.alert_success') }}');
                    this.showLeadModal = false;
                    this.lead_name = '';
                    this.lead_email = '';
                    this.lead_phone = '';
                } catch(e) {
                    console.error(e);
                    alert('{{ __('tools.imt.alert_error') }}');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>

@endsection