<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Mahidio Dashboard') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside 
            :class="sidebarOpen ? 'w-72' : 'w-20'" 
            class="relative z-20 flex-shrink-0 bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out hidden md:flex flex-col">
            
            <div class="flex items-center justify-between h-20 px-6 bg-slate-950">
                <div class="flex items-center gap-3" x-show="sidebarOpen">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center font-bold text-white">M</div>
                    <span class="text-xl font-bold text-white tracking-tight">Mahidio</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-slate-800 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @include('layouts.partials.sidebar-links')
            </nav>

            <div class="p-4 bg-slate-950">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center w-full gap-3 px-4 py-3 text-sm font-medium hover:text-white hover:bg-red-500/10 rounded-xl transition text-red-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="sidebarOpen">Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- TOPBAR -->
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10">
                <h2 class="text-xl font-semibold text-slate-800">{{ $header ?? 'Tableau de bord' }}</h2>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-indigo-600 uppercase tracking-widest">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-200">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>