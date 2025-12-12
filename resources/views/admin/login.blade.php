<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | House Team</title>
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-ht-navy font-sans antialiased text-white h-screen flex items-center justify-center relative overflow-hidden">

    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-40">
        <source src="{{ asset('video/header_bg.mp4') }}" type="video/mp4">
    </video>
    
    <div class="absolute inset-0 bg-gradient-to-t from-ht-navy via-ht-navy/60 to-transparent"></div>

    <div class="w-full max-w-md bg-white/10 backdrop-blur-xl border border-white/20 p-10 rounded-3xl shadow-2xl relative z-10 transform transition-all hover:scale-[1.01]">
        <div class="text-center mb-10">
            <h1 class="font-black text-3xl mb-2 tracking-tighter">HOUSE TEAM<span class="text-ht-blue">.</span></h1>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-300 font-bold">Painel Administrativo</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="email" class="text-xs font-bold uppercase tracking-widest text-slate-300 ml-1">Email</label>
                <div class="relative">
                    <input type="email" name="email" id="email" required autofocus
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all"
                           placeholder="admin@houseteam.pt">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                </div>
                @error('email')
                    <span class="text-red-400 text-xs font-bold ml-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-xs font-bold uppercase tracking-widest text-slate-300 ml-1">Senha</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                           class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all"
                           placeholder="••••••••">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="accent-ht-blue w-4 h-4 rounded cursor-pointer">
                    <span class="text-xs text-slate-300 font-medium group-hover:text-white transition-colors">Lembrar-me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-ht-blue text-white font-black py-4 uppercase tracking-widest text-xs rounded-xl hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95">
                Aceder ao Painel
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-xs font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar ao Site
            </a>
        </div>
    </div>

</body>
</html>