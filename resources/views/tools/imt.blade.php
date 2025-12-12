@extends('layouts.app')

@section('content')

<div class="bg-ht-navy text-white pt-32 pb-24 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    
    <div class="container mx-auto px-6 relative z-10" data-aos="fade-up">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">Impostos & Taxas</p>
        <h1 class="text-3xl md:text-5xl font-black mb-6 tracking-tight">Simulador IMT 2025</h1>
        <p class="text-slate-400 font-medium max-w-2xl mx-auto text-lg">
            Calcule o IMT e Imposto de Selo, com as novas regras de isenção para jovens.
        </p>
    </div>
</div>

<section class="py-16 bg-slate-50 relative" x-data="imtCalculator()" x-init="calculate()">
    <div class="container mx-auto px-6 md:px-12 relative -mt-20 z-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="flex items-center gap-4 mb-8 border-b border-slate-100 pb-6">
                        <div class="w-10 h-10 rounded-full bg-ht-accent text-white flex items-center justify-center font-bold text-sm">1</div>
                        <h3 class="text-xl font-bold text-ht-navy">Dados do Imóvel</h3>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Valor de Aquisição (€)</label>
                        <input type="number" x-model.number="propertyValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-lg font-bold text-ht-navy focus:outline-none focus:ring-2 focus:ring-ht-accent transition-all" placeholder="350000">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Localização</label>
                            <div class="relative">
                                <select x-model="location" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-ht-accent appearance-none">
                                    <option value="continente">Portugal Continental</option>
                                    <option value="ilhas">Açores / Madeira</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Finalidade</label>
                            <div class="relative">
                                <select x-model="purpose" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-ht-accent appearance-none">
                                    <option value="hpp">Habitação Própria Permanente</option>
                                    <option value="secundaria">Habitação Secundária</option>
                                    <option value="rustico">Prédio Rústico</option>
                                    <option value="outros">Outros (Comércio/Terrenos)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 md:p-10 rounded-[2rem] shadow-xl border border-slate-100" x-show="purpose === 'hpp'" x-transition>
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-bold text-sm">2</div>
                        <h3 class="text-xl font-bold text-ht-navy">Benefício IMT Jovem</h3>
                    </div>
                    
                    <label class="flex items-center gap-4 p-4 bg-green-50 border border-green-100 rounded-xl cursor-pointer hover:bg-green-100 transition-colors">
                        <input type="checkbox" x-model="isYoung" @change="calculate()" class="accent-green-600 w-5 h-5 rounded">
                        <div>
                            <span class="text-sm font-bold text-green-800 block">Compradores têm até 35 anos?</span>
                            <span class="text-xs text-green-600">Isenção total até 324.058€ (Regras 2025).</span>
                        </div>
                    </label>
                </div>

            </div>

            <div class="lg:col-span-5">
                <div class="sticky top-32 bg-ht-navy text-white p-10 rounded-[2.5rem] shadow-2xl border border-white/10">
                    <h3 class="text-xl font-bold mb-8 text-ht-accent tracking-tight">Impostos a Pagar</h3>

                    <div class="space-y-5 text-sm font-medium text-slate-300">
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span>IMT Calculado</span>
                            <span class="text-white font-bold">€ <span x-text="formatMoney(imt)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10" x-show="isYoung && purpose === 'hpp'">
                            <span>Desconto Jovem</span>
                            <span class="text-green-400 font-bold">- € <span x-text="formatMoney(youthDiscount)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span>Imposto de Selo (0,8%)</span>
                            <span class="text-white font-bold">€ <span x-text="formatMoney(stampDuty)"></span></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10" x-show="isYoung && purpose === 'hpp'">
                            <span>Desconto Selo Jovem</span>
                            <span class="text-green-400 font-bold">- € <span x-text="formatMoney(youthStampDiscount)"></span></span>
                        </div>
                    </div>

                    <div class="bg-white/10 rounded-2xl p-6 mt-8 backdrop-blur-sm border border-white/10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total a Pagar</p>
                        <p class="text-4xl font-black text-ht-accent">€ <span x-text="formatMoney(total)"></span></p>
                    </div>

                    <div class="mt-10">
                        <a href="{{ route('portfolio') }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg transform active:scale-95">
                            Procurar Imóveis
                        </a>
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <button @click="showHelp = true" class="text-ht-navy text-xs font-bold uppercase tracking-widest hover:text-ht-accent hover:underline flex items-center justify-end gap-2 ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Ver Tabelas
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL AJUDA --}}
    <div x-show="showHelp" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-ht-navy/80 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden" @click.away="showHelp = false">
            <div class="bg-ht-navy p-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Tabelas IMT 2025</h3>
                <button @click="showHelp = false" class="text-white/50 hover:text-white transition">✕</button>
            </div>
            <div class="p-8 text-slate-600 text-sm leading-relaxed">
                <p><strong>HPP (Habitação Própria):</strong> Isento até 104.261€. Taxas progressivas a partir daí.</p>
                <p><strong>IMT Jovem:</strong> Isenção total até 324.058€ para jovens até 35 anos na 1ª habitação.</p>
                <p><strong>Habitação Secundária:</strong> Taxas desde 1%.</p>
                <p><strong>Outros:</strong> Rústicos (5%), Comércio/Terrenos (6,5%).</p>
            </div>
            <div class="p-6 bg-slate-50 border-t border-slate-100 text-right">
                <button @click="showHelp = false" class="text-xs font-bold text-ht-accent uppercase tracking-widest hover:underline">Fechar</button>
            </div>
        </div>
    </div>

</section>

<script>
    function imtCalculator() {
        return {
            showHelp: false, propertyValue: 0, location: 'continente', purpose: 'hpp', isYoung: false,
            imt: 0, stampDuty: 0, youthDiscount: 0, youthStampDiscount: 0, total: 0,
            formatMoney(value) { return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2 }).format(value); },
            calculate() {
                let valor = this.propertyValue || 0;
                let taxa = 0, parcela = 0, imtCalc = 0;

                // Lógica Simplificada HPP Continente 2025
                if (this.purpose === 'hpp') {
                    if (valor <= 104261) { taxa = 0; parcela = 0; }
                    else if (valor <= 142618) { taxa = 0.02; parcela = 2085.22; }
                    else if (valor <= 194458) { taxa = 0.05; parcela = 6363.76; }
                    else if (valor <= 324058) { taxa = 0.07; parcela = 10252.92; }
                    else if (valor <= 648022) { taxa = 0.08; parcela = 13493.50; }
                    else { taxa = 0.06; parcela = 0; } // Taxa única simplificada
                    imtCalc = Math.max(0, (valor * taxa) - parcela);
                } else { imtCalc = valor * 0.065; } // Genérico para outros

                this.imt = imtCalc;
                this.stampDuty = valor * 0.008;

                this.youthDiscount = 0; this.youthStampDiscount = 0;
                if (this.isYoung && this.purpose === 'hpp' && valor <= 324058) {
                    this.youthDiscount = this.imt;
                    this.youthStampDiscount = this.stampDuty;
                }

                this.total = (this.imt - this.youthDiscount) + (this.stampDuty - this.youthStampDiscount);
            }
        }
    }
</script>

@endsection