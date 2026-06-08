<x-app-layout>
    <x-slot name="header">
        @if(Auth::user()->role === 'admin')
            Console de Gouvernance Système
        @elseif(Auth::user()->role === 'reserviste')
            Gestion Opérationnelle des Salles
        @elseif(Auth::user()->role === 'responsable')
            Audit & Rapports Financiers
        @else
            Espace Personnel Mahidio
        @endif
    </x-slot>

    <div class="space-y-8">
        
        {{-- 1. SECTION DES STATISTIQUES RÉACTIVES (Filtrage strict par composant) --}}
        @if(Auth::user()->role === 'admin')
            <livewire:admin.dashboard-stats />
        @elseif(Auth::user()->role === 'reserviste')
            <livewire:reserviste.dashboard-stats />
        @elseif(Auth::user()->role === 'responsable')
            <livewire:responsable.dashboard-stats />
        @else
            <livewire:client.dashboard-stats />
        @endif

        {{-- 2. CORPS DU DASHBOARD : SÉPARATION DES RESPONSABILITÉS --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- --- CAS DU RÉSERVISTE : Planning & Disponibilité --- --}}
            @if(Auth::user()->role === 'reserviste')
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest">Planning d'occupation</h3>
                            <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase">Direct</span>
                        </div>
                        <livewire:reserviste.calendar />
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <livewire:reserviste.availability-checker />
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-tight">Protocole Réserviste</h3>
                        <ul class="space-y-4 text-xs font-medium">
                            <li class="flex gap-3 text-slate-500"><span class="text-indigo-600">01.</span> Vérifier la date</li>
                            <li class="flex gap-3 text-slate-500"><span class="text-indigo-600">02.</span> Fixer le tarif</li>
                            <li class="flex gap-3 text-slate-500"><span class="text-indigo-600">03.</span> Encaisser le paiement</li>
                        </ul>
                    </div>
                </div>

            {{-- --- CAS DE L'ADMIN : Gestion des utilisateurs uniquement --- --}}
            @elseif(Auth::user()->role === 'admin')
                <div class="lg:col-span-8">
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 text-sm uppercase tracking-widest">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Journal des Inscriptions
                        </h3>
                        <livewire:admin.recent-users />
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="bg-slate-900 p-8 rounded-[2rem] text-white shadow-2xl">
                        <h3 class="font-bold text-lg mb-6 tracking-tight">Maintenance Système</h3>
                        <div class="space-y-4">
                            <a href="{{ route('admin.users') }}" class="flex items-center gap-4 p-4 bg-white/10 rounded-2xl hover:bg-white/20 transition group">
                                <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center font-bold">U</div>
                                <div>
                                    <p class="text-sm font-bold">Accès Staff</p>
                                    <p class="text-[10px] text-slate-400">Gérer les privilèges</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            {{-- --- CAS DU RESPONSABLE : Accès exclusif aux rapports financiers --- --}}
            @elseif(Auth::user()->role === 'responsable')
                <div class="lg:col-span-12">
                    <div class="bg-white p-12 rounded-[3rem] border border-slate-200 shadow-sm text-center relative overflow-hidden">
                        <div class="relative z-10">
                            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H5a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h2 class="text-3xl font-bold text-slate-900 mb-4 font-serif-luxury">Espace Décisionnel</h2>
                            <p class="text-slate-500 max-w-xl mx-auto mb-10 italic">Accédez à l'audit complet des transactions, analysez les performances par type de cérémonie et exportez vos rapports officiels.</p>
                            <a href="{{ route('responsable.reports') }}" class="inline-block bg-indigo-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-indigo-700 transition">
                                Ouvrir les rapports d'audit
                            </a>
                        </div>
                    </div>
                </div>

            {{-- --- CAS DU CLIENT --- --}}
            @elseif(Auth::user()->role === 'client')
                <div class="lg:col-span-12">
                    <div class="bg-indigo-600 p-12 rounded-[3rem] text-white shadow-2xl relative overflow-hidden text-center md:text-left">
                        <div class="relative z-10 max-w-2xl">
                            <h2 class="text-4xl font-bold mb-4 font-serif-luxury">Bienvenue à la Salle Mahidio</h2>
                            <p class="text-indigo-100 text-lg mb-10 leading-relaxed font-light">Réservez en ligne le cadre idéal pour vos cérémonies. Suivez l'état de votre dossier et téléchargez vos reçus officiels en un clic.</p>
                            <a href="{{ route('client.new-booking') }}" class="inline-block bg-white text-indigo-700 px-12 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl hover:bg-indigo-50 transition">
                                Nouvelle demande de réservation
                            </a>
                        </div>
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>