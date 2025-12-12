<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Imóveis | House Team Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all">
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    Meus Imóveis
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8 md:p-12 overflow-y-auto bg-slate-50">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-ht-navy tracking-tight">Gerir Imóveis</h2>
                    <p class="text-slate-500 text-sm mt-1 font-medium">Lista completa do seu portfólio.</p>
                </div>
                <a href="{{ route('admin.properties.create') }}" class="flex items-center gap-2 bg-ht-blue text-white px-6 py-3 rounded-xl shadow-lg hover:bg-blue-600 transition-all font-bold uppercase text-xs tracking-widest transform hover:-translate-y-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Novo Imóvel
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase text-slate-400 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Capa</th>
                            <th class="px-6 py-4">Título</th>
                            <th class="px-6 py-4">Preço</th>
                            <th class="px-6 py-4">Localização</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($properties as $property)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 w-24">
                                <div class="w-16 h-12 bg-slate-200 rounded-lg overflow-hidden relative shadow-sm">
                                    @if($property->cover_image)
                                        <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-xs">Sem Ft</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700 text-sm group-hover:text-ht-blue transition-colors">{{ Str::limit($property->title, 40) }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-ht-navy">€ {{ number_format($property->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-500">{{ $property->location }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $property->status == 'Venda' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                    {{ $property->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2 items-center h-full">
                                <a href="{{ route('admin.properties.edit', $property) }}" class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-ht-blue hover:text-white transition-all shadow-sm hover:shadow">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                                
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Tem certeza?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:bg-red-500 hover:text-white transition-all shadow-sm hover:shadow">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $properties->links() }}
            </div>
        </main>
    </div>
</body>
</html>