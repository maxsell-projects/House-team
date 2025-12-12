@extends('layouts.app')

@section('content')

<div class="bg-ht-navy text-white pt-32 pb-24 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    
    <div class="container mx-auto px-6 relative z-10" data-aos="fade-up">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">Ferramentas Financeiras</p>
        <h1 class="text-3xl md:text-5xl font-black mb-6 tracking-tight">Simulador de Crédito Habitação</h1>
        <p class="text-slate-400 font-medium max-w-2xl mx-auto text-lg">
            Planeie o seu futuro com precisão. Calcule a prestação mensal com base nas taxas Euribor atuais.
        </p>
    </div>
</div>

<section class="py-16 bg-slate-50 relative" 
         x-data="creditCalculator()" 
         x-init="calculate()">
    
    <div class="container mx-auto px-6 md:px-12 relative -mt-20 z-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- COLUNA ESQUERDA: DADOS --}}
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="text-xl font-bold text-ht-navy">Dados do Financiamento</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor do Imóvel (€)</label>
                            <input type="number" x-model.number="propertyValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="350000">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Entrada Inicial (€)</label>
                            <input type="number" x-model.number="downPayment" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="35000">
                            <p class="text-[10px] text-slate-400 mt-2 font-medium" x-show="percentage < 10">
                                <span class="text-red-500 font-bold">Atenção:</span> Mínimo recomendado de 10%.
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">Prazo (Anos)</label>
                            <span class="text-ht-accent font-black text-lg" x-text="years + ' Anos'"></span>
                        </div>
                        <input type="range" x-model.number="years" @input="calculate()" min="10" max="40" step="1" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-ht-accent hover:accent-ht-navy transition-all">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Euribor</label>
                            <div class="relative">
                                <select x-model.number="euriborRate" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-ht-accent appearance-none">
                                    <option value="2.168">6 Meses (2.168%)</option>
                                    <option value="2.088">3 Meses (2.088%)</option>
                                    <option value="2.268">12 Meses (2.268%)</option>
                                    <option value="3.0">Taxa Fixa (3.0%)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Spread (%)</label>
                            <input type="number" x-model.number="spread" @input="calculate()" step="0.1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="1.0">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">TAN Global</label>
                            <div class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 cursor-not-allowed">
                                <span x-text="(euriborRate + spread).toFixed(3)"></span> %
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="text-xl font-bold text-ht-navy">Análise de Viabilidade</h3>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Rendimento Mensal Líquido do Agregado (€)</label>
                        <input type="number" x-model.number="monthlyIncome" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="Ex: 2500">
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wide mb-3">
                            <span class="text-slate-500">Taxa de Esforço</span>
                            <span :class="effortRate > 35 ? 'text-red-500' : 'text-green-500'" x-text="effortRate + '%'"></span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700 ease-out" 
                                 :class="effortRate > 35 ? 'bg-red-500' : 'bg-green-500'" 
                                 :style="'width: ' + Math.min(effortRate, 100) + '%'"></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-3 font-medium flex items-center gap-2">
                            <span x-show="effortRate <= 35" class="text-green-600">✓ Taxa saudável (Abaixo de 35%)</span>
                            <span x-show="effortRate > 35 && effortRate <= 50" class="text-yellow-600">⚠️ Taxa elevada (Entre 35% e 50%)</span>
                            <span x-show="effortRate > 50" class="text-red-500">⛔ Risco de rejeição (Acima de 50%)</span>
                        </p>
                    </div>
                </div>

            </div>

            {{-- COLUNA DIREITA: RESULTADOS (STICKY) --}}
            <div class="lg:col-span-5">
                <div class="sticky top-32 bg-ht-navy text-white p-10 rounded-[2.5rem] shadow-2xl border border-white/10">
                    <h3 class="text-xl font-bold mb-8 text-ht-accent tracking-tight">A Sua Prestação Mensal</h3>

                    <div class="text-center mb-10 pb-10 border-b border-white/10">
                        <p class="text-6xl font-black tracking-tighter mb-2">€ <span x-text="formatMoney(monthlyPayment)"></span></p>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 opacity-60">+ Seguros Est. € <span x-text="formatMoney(insuranceCost)"></span></p>
                    </div>

                    <div class="space-y-5 text-sm font-medium text-slate-300">
                        <div class="flex justify-between items-center">
                            <span>Montante Financiado</span>
                            <span class="text-white font-bold">€ <span x-text="formatMoney(loanAmount)"></span></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Juros Totais</span>
                            <span class="text-white font-bold">€ <span x-text="formatMoney(totalInterest)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-white/10">
                            <span class="text-ht-accent font-bold uppercase tracking-wide">MTIC (Total)</span>
                            <span class="text-xl font-black text-white">€ <span x-text="formatMoney(totalAmount)"></span></span>
                        </div>
                    </div>

                    <div class="mt-10 space-y-4">
                        <a href="{{ route('contact') }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg transform active:scale-95">
                            Pedir Proposta Bancária
                        </a>
                        <a href="{{ route('portfolio') }}" class="block w-full border border-white/20 text-white font-bold uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-white/10 transition-all text-center">
                            Ver Imóveis Compatíveis
                        </a>
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <button @click="showHelp = true" class="text-ht-navy text-xs font-bold uppercase tracking-widest hover:text-ht-accent hover:underline flex items-center justify-end gap-2 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Entenda as taxas
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE AJUDA --}}
    <div x-show="showHelp" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-ht-navy/80 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.away="showHelp = false">
            <div class="bg-ht-navy p-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Sobre o Crédito Habitação</h3>
                <button @click="showHelp = false" class="text-white/50 hover:text-white transition">✕</button>
            </div>
            <div class="p-8 space-y-6 text-slate-600 text-sm leading-relaxed overflow-y-auto max-h-[70vh]">
                <p><strong>Euribor + Spread:</strong> A prestação é calculada somando a taxa de referência europeia (Euribor) com a margem do banco (Spread).</p>
                <p><strong>Taxa de Esforço:</strong> O Banco de Portugal recomenda que as prestações não ultrapassem 35% a 50% do seu rendimento líquido.</p>
                <p><strong>Seguros:</strong> Incluímos uma estimativa de Seguro de Vida e Multirriscos, obrigatórios no crédito habitação.</p>
                <p><strong>Entrada:</strong> Geralmente é necessário ter capitais próprios de pelo menos 10% do valor do imóvel.</p>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 text-right">
                <button @click="showHelp = false" class="text-xs font-bold text-ht-accent uppercase tracking-widest hover:underline">Fechar</button>
            </div>
        </div>
    </div>

</section>

<script>
    function creditCalculator() {
        return {
            showHelp: false,
            propertyValue: 350000,
            downPayment: 35000,
            years: 30,
            euriborRate: 2.168,
            spread: 1.0,
            monthlyIncome: 2500,
            
            loanAmount: 0,
            monthlyPayment: 0,
            totalInterest: 0,
            totalAmount: 0,
            percentage: 0,
            insuranceCost: 0,
            effortRate: 0,

            formatMoney(value) {
                return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            },

            calculate() {
                this.loanAmount = Math.max(0, this.propertyValue - this.downPayment);
                
                this.percentage = this.propertyValue > 0 ? (this.downPayment / this.propertyValue) * 100 : 0;

                let annualRate = (this.euriborRate + this.spread) / 100;
                let monthlyRate = annualRate / 12;
                let totalMonths = this.years * 12;

                if (monthlyRate > 0) {
                    this.monthlyPayment = this.loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, totalMonths)) / (Math.pow(1 + monthlyRate, totalMonths) - 1);
                } else {
                    this.monthlyPayment = this.loanAmount / totalMonths;
                }

                this.totalAmount = this.monthlyPayment * totalMonths;
                this.totalInterest = this.totalAmount - this.loanAmount;
                this.insuranceCost = this.loanAmount * 0.00045; 

                let totalMonthlyCost = this.monthlyPayment + this.insuranceCost;
                this.effortRate = this.monthlyIncome > 0 ? Math.round((totalMonthlyCost / this.monthlyIncome) * 100 * 10) / 10 : 0;
            }
        }
    }
</script>

@endsection