@php
    $role = Auth::user()->role;
    $linkClass = "flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition duration-200 hover:text-white hover:bg-slate-800 ";
    $activeClass = "bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-900/20";
@endphp

<!-- LIEN COMMUN : TABLEAU DE BORD PERSONNEL -->
<a href="{{ route('dashboard') }}" 
   class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : '' }}" 
   wire:navigate>
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    <span x-show="sidebarOpen">Mon Dashboard</span>
</a>

@if($role === 'admin')
    <div class="pt-4 pb-2 text-[10px] font-bold uppercase text-slate-500 tracking-widest px-4" x-show="sidebarOpen">Administration</div>
    
    <a href="{{ route('admin.users') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('admin.users') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span x-show="sidebarOpen">Comptes Utilisateurs</span>
    </a>
@endif

<!-- TÂCHES RÉSERVISTE : Gérer réservations & Caisse (Page 26 & 31) -->
@if($role === 'reserviste')
    <div class="pt-4 pb-2 text-[10px] font-bold uppercase text-slate-500 tracking-widest px-4" x-show="sidebarOpen">Opérations</div>
    
    <a href="{{ route('reserviste.bookings') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('reserviste.bookings') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <span x-show="sidebarOpen">Suivi Réservations</span>
    </a>
    
    <a href="{{ route('reserviste.caisse') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('reserviste.caisse') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span x-show="sidebarOpen">Tenue de la Caisse</span>
    </a>
@endif

<!-- TÂCHES RESPONSABLE : Consulter Rapports (Page 26 & 33) -->
@if($role === 'responsable')
    <div class="pt-4 pb-2 text-[10px] font-bold uppercase text-slate-500 tracking-widest px-4" x-show="sidebarOpen">Audit</div>
    
    <a href="{{ route('responsable.reports') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('responsable.reports') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
        <span x-show="sidebarOpen">Consulter Rapports</span>
    </a>
@endif

<!-- TÂCHES CLIENT : Nouvelle Réservation & Suivi -->
@if($role === 'client')
    <div class="pt-4 pb-2 text-[10px] font-bold uppercase text-slate-500 tracking-widest px-4" x-show="sidebarOpen">Mes Services</div>
    
    <a href="{{ route('client.new-booking') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('client.new-booking') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span x-show="sidebarOpen">Nouvelle Réservation</span>
    </a>

    <a href="{{ route('client.my-bookings') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('client.my-bookings') ? $activeClass : '' }}" 
       wire:navigate>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        <span x-show="sidebarOpen">Mes Demandes</span>
    </a>
@endif