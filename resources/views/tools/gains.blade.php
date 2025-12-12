@extends('layouts.app')

@section('content')

<div class="bg-ht-navy text-white pt-32 pb-24 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    
    <div class="container mx-auto px-6 relative z-10" data-aos="fade-up">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">Fiscalidade</p>
        <h1 class="text-3xl md:text-5xl font-black mb-6 tracking-tight">Cálculo de Mais-Valias</h1>
        <p class="text-slate-400 font-medium max-w-2xl mx-auto text-lg">
            Estime o imposto sobre a venda do seu imóvel, considerando reinvestimento e coeficientes de inflação.
        </p>
    </div>
</div>

<section class="py-16 bg-slate-50 relative" x-data="capitalGainsCalculator()" x-init="calculate()">
    <div class="container mx-auto px-6 md:px-12 relative -mt-20 z-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- FORMULÁRIO --}}
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="text-xl font-bold text-ht-navy">Dados da Venda</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor Venda (€)</label>
                            <input type="number" x-model.number="saleValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="350000">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Encargos Venda (€)</label>
                            <input type="number" x-model.number="saleExpenses" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="Comissões...">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="text-xl font-bold text-ht-navy">Dados da Aquisição</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor Compra (€)</label>
                            <input type="number" x-model.number="purchaseValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="150000">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Ano Aquisição</label>
                            <div class="relative">
                                <select x-model.number="purchaseYear" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-ht-accent appearance-none">
                                    <template x-for="year in years" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <p x-show="purchaseYear < 1989" class="text-[10px] text-green-600 mt-2 font-bold flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Isento (Pré-1989)
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Encargos Compra (€)</label>
                            <input type="number" x-model.number="purchaseExpenses" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="IMT, Escritura...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Obras (12 anos) (€)</label>
                            <input type="number" x-model.number="improvements" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="Obras c/ fatura">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">3</div>
                        <h3 class="text-xl font-bold text-ht-navy">Situação Fiscal</h3>
                    </div>
                    
                    <div class="mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="isHPP" @change="calculate()" class="accent-ht-accent w-5 h-5 rounded border-slate-300">
                            <span class="text-sm font-bold text-ht-navy">Era Habitação Própria e Permanente (HPP)?</span>
                        </label>

                        <div x-show="isHPP" x-collapse class="mt-4 space-y-4 pt-4 border-t border-slate-200">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor Empréstimo a Liquidar (€)</label>
                                <input type="number" x-model.number="loanValue" @input="calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor a Reinvestir (€)</label>
                                <input type="number" x-model.number="reinvestmentValue" @input="calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Rendimentos Anuais (IRS) (€)</label>
                        <input type="number" x-model.number="annualIncome" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="Salário bruto anual">
                    </div>
                </div>

            </div>

            {{-- RESULTADOS --}}
            <div class="lg:col-span-5">
                <div class="sticky top-32 bg-ht-navy text-white p-10 rounded-[2.5rem] shadow-2xl border border-white/10">
                    <h3 class="text-xl font-bold mb-8 text-ht-accent tracking-tight">Resultado da Simulação</h3>

                    <div class="space-y-5 text-sm font-medium text-slate-300">
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span>Mais-Valia Bruta</span>
                            <span class="text-white">€ <span x-text="formatMoney(grossGain)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span>Coeficiente Inflação (<span x-text="coefficient"></span>)</span>
                            <span class="text-green-400 font-bold">- € <span x-text="formatMoney(inflationDeduction)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10" x-show="isHPP && reinvestmentBenefit > 0">
                            <span>Benefício Reinvestimento</span>
                            <span class="text-green-400 font-bold">- € <span x-text="formatMoney(reinvestmentBenefit)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-white font-bold">Mais-Valia Tributável (50%)</span>
                            <span class="text-white font-bold">€ <span x-text="formatMoney(taxableGain)"></span></span>
                        </div>
                    </div>

                    <div class="bg-white/10 rounded-2xl p-6 mt-8 backdrop-blur-sm border border-white/10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Imposto Estimado (IRS)</p>
                        <p class="text-4xl font-black text-ht-accent">€ <span x-text="formatMoney(estimatedTax)"></span></p>
                        <p class="text-[10px] text-slate-400 mt-3 italic leading-relaxed">*Valor indicativo baseado nos escalões de IRS 2024. Não dispensa consulta profissional.</p>
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('contact') }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg transform active:scale-95">
                            Falar com um Consultor
                        </a>
                    </div>
                </div>

                {{-- BOTÃO TRANSPARÊNCIA / AJUDA --}}
                <div class="text-right mt-4">
                    <button @click="showHelp = true" class="text-ht-navy text-xs font-bold uppercase tracking-widest hover:text-ht-accent hover:underline flex items-center justify-end gap-2 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Entenda o Cálculo
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE AJUDA --}}
    <div x-show="showHelp" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-ht-navy/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="showHelp = false">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden relative">
            <div class="bg-ht-navy p-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Como funciona o cálculo?</h3>
                <button @click="showHelp = false" class="text-white/50 hover:text-white transition">✕</button>
            </div>
            <div class="p-8 space-y-6 text-slate-600 text-sm leading-relaxed overflow-y-auto max-h-[70vh]">
                
                <div>
                    <h4 class="font-bold text-ht-navy mb-2 text-base">1. Fórmula Geral</h4>
                    <p class="bg-slate-50 p-4 rounded-xl border border-slate-200 font-mono text-xs">
                        Mais-Valia = Valor Venda - (Valor Aquisição × Coeficiente) - Despesas
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-ht-navy mb-2">2. Coeficiente de Desvalorização</h4>
                    <p>
                        O Estado aplica um multiplicador ao valor de compra para corrigir a inflação. Imóveis comprados há mais tempo têm um coeficiente maior, reduzindo o imposto a pagar. Utilizamos os coeficientes da Portaria mais recente.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-ht-navy mb-2">3. Tributação (IRS)</h4>
                    <p>
                        Apenas 50% do lucro (mais-valia) é tributado. Este valor é somado aos seus rendimentos anuais (salários, pensões) e taxado de acordo com o seu escalão de IRS.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-ht-navy mb-2">4. Isenção por Reinvestimento</h4>
                    <p>
                        Se o imóvel vendido era sua Habitação Própria e Permanente (HPP) e você usar o dinheiro para comprar outra HPP (em até 36 meses), o imposto pode ser reduzido ou anulado.
                    </p>
                </div>

            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 text-right">
                <button @click="showHelp = false" class="text-xs font-bold text-ht-accent uppercase tracking-widest hover:underline">Fechar</button>
            </div>
        </div>
    </div>

</section>

<script>
    function capitalGainsCalculator() {
        return {
            showHelp: false,
            saleValue: 0, saleExpenses: 0, purchaseValue: 0, purchaseYear: new Date().getFullYear(),
            purchaseExpenses: 0, improvements: 0, isHPP: false, loanValue: 0, reinvestmentValue: 0,
            annualIncome: 30000, years: [],
            coefficients: { 2024: 1.00, 2023: 1.00, 2022: 1.01, 2021: 1.01, 2020: 1.01, 2010: 1.08, 2000: 1.35, 1990: 2.50 },
            grossGain: 0, inflationDeduction: 0, reinvestmentBenefit: 0, taxableGain: 0, estimatedTax: 0, coefficient: 1,

            init() {
                const currentYear = new Date().getFullYear();
                for (let i = currentYear; i >= 1980; i--) this.years.push(i);
            },
            getCoef(year) {
                if (year >= 2023) return 1.00;
                if (this.coefficients[year]) return this.coefficients[year];
                return 1.25; 
            },
            formatMoney(value) {
                return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            },
            calculate() {
                if (this.purchaseYear < 1989) { this.estimatedTax = 0; this.taxableGain = 0; return; }
                
                this.coefficient = this.getCoef(this.purchaseYear);
                const purchaseUpdated = this.purchaseValue * this.coefficient;
                this.inflationDeduction = purchaseUpdated - this.purchaseValue;
                
                const totalExpenses = (this.saleExpenses||0) + (this.purchaseExpenses||0) + (this.improvements||0);
                let gain = (this.saleValue||0) - purchaseUpdated - totalExpenses;
                if (gain < 0) gain = 0;
                this.grossGain = gain;

                let taxableAmount = gain;
                this.reinvestmentBenefit = 0;

                if (this.isHPP && gain > 0 && this.reinvestmentValue > 0) {
                    const netSaleValue = Math.max(0, (this.saleValue||0) - (this.loanValue||0));
                    if (netSaleValue > 0) {
                        const ratio = Math.min(1, this.reinvestmentValue / netSaleValue);
                        this.reinvestmentBenefit = gain * ratio;
                        taxableAmount = gain - this.reinvestmentBenefit;
                    }
                }

                this.taxableGain = taxableAmount * 0.5;
                const totalIncome = (this.annualIncome||0) + this.taxableGain;
                let rate = 0.13;
                if (totalIncome > 20000) rate = 0.28; 
                if (totalIncome > 40000) rate = 0.35; 
                if (totalIncome > 80000) rate = 0.48;
                
                this.estimatedTax = this.taxableGain * rate;
            }
        }
    }
</script>

@endsection