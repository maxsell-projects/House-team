<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | House Team</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        'ht-navy': '#0f172a',
                        'ht-blue': '#2563eb',
                        'ht-light': '#f8fafc',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-ht-navy text-white flex flex-col shadow-2xl z-20">
            <div class="p-8 text-center border-b border-white/10">
                <h1 class="font-black text-2xl tracking-tighter">HOUSE TEAM<span class="text-ht-blue">.</span></h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold">Admin Panel</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:text-ht-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Meus Imóveis
                </a>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all group">
                    <svg class="w-5 h-5 group-hover:text-ht-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ver Site
                </a>
            </nav>

            <div class="p-4 border-t border-white/10">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-red-500/10 hover:border hover:border-red-500/20 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Terminar Sessão
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50">
            <div class="p-8 md:p-12">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-ht-navy tracking-tight">Dashboard</h2>
                        <p class="text-slate-500 text-sm mt-1 font-medium">Bem-vindo de volta, a sua imobiliária está a crescer.</p>
                    </div>
                    <a href="{{ route('admin.properties.create') }}" class="flex items-center gap-2 bg-ht-blue text-white px-6 py-3 rounded-xl shadow-lg hover:bg-blue-600 transition-all font-bold uppercase text-xs tracking-widest transform hover:-translate-y-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Adicionar Imóvel
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-24 h-24 text-ht-navy" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Total de Imóveis</p>
                            <p class="text-5xl font-black text-ht-navy">{{ \App\Models\Property::count() }}</p>
                            <p class="text-xs text-green-500 font-bold mt-4 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                Portfólio Ativo
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-24 h-24 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Para Venda</p>
                            <p class="text-5xl font-black text-green-600">{{ \App\Models\Property::where('status', 'Venda')->count() }}</p>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
                                <div class="bg-green-500 h-1.5 rounded-full" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg class="w-24 h-24 text-ht-blue" fill="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Para Arrendamento</p>
                            <p class="text-5xl font-black text-ht-blue">{{ \App\Models\Property::where('status', 'Arrendamento')->count() }}</p>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-4">
                                <div class="bg-ht-blue h-1.5 rounded-full" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-ht-navy">Últimas Adições</h3>
                        <a href="{{ route('admin.properties.index') }}" class="text-xs font-bold uppercase tracking-widest text-ht-blue hover:underline">Ver Todos</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold tracking-wider">
                                <tr>
                                    <th class="px-8 py-4">Imóvel</th>
                                    <th class="px-8 py-4">Valor</th>
                                    <th class="px-8 py-4">Zona</th>
                                    <th class="px-8 py-4">Estado</th>
                                    <th class="px-8 py-4 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach(\App\Models\Property::latest()->take(5)->get() as $property)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-lg bg-slate-200 overflow-hidden flex-shrink-0 relative">
                                                @if($property->cover_image)
                                                    <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-700 group-hover:text-ht-blue transition-colors text-sm">{{ Str::limit($property->title, 35) }}</p>
                                                <p class="text-xs text-slate-400">{{ $property->type }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 font-bold text-slate-700 text-sm">€ {{ number_format($property->price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-4 text-sm font-medium text-slate-500">{{ $property->location }}</td>
                                    <td class="px-8 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $property->status == 'Venda' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            {{ $property->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('admin.properties.edit', $property) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-ht-blue hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>