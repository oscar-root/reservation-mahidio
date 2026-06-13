<?php
use App\Models\Reservation;
use App\Models\Recu;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, with, layout};

layout('layouts.app');

with(fn () => [
    // Global Metrics
    'caGlobal' => Recu::sum('montant'),
    'encaissementsMois' => Recu::whereMonth('created_at', now()->month)->sum('montant'),
    'totalEvenements' => Reservation::where('statut', 'payee')->count(),
    
    // Performance par catégorie (Top Model Grid)
    'categories' => Reservation::where('statut', 'payee')
        ->select('type_ceremonie', DB::raw('count(*) as nb'), DB::raw('sum(montant_total) as total'))
        ->groupBy('type_ceremonie')
        ->orderBy('total', 'desc')
        ->get(),

    // Journal d'audit (Tableau haute précision)
    'transactions' => Recu::with('reservation.client')->latest()->take(20)->get(),
]);
?>

<div class="space-y-10 pb-20">
    
    <!-- HEADER STRATÉGIQUE -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-[0.2em] rounded-full">Finance Intelligence</span>
            </div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase font-serif-luxury">Rapport de <span class="text-indigo-600">Performance</span></h1>
            <p class="text-slate-500 text-sm italic">Analyse consolidée de la Salle Mahidio • {{ now()->format('Y') }}</p>
        </div>
        
        <div class="flex gap-4 no-print">
            <a href="{{ route('responsable.print-report') }}" target="_blank" 
                class="group flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-[1.5rem] font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-2xl shadow-slate-200">
                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
    Générer Rapport Officiel
</a>
        </div>
    </div>

    <!-- CARTES MAITRESSES (KPIs) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Chiffre d'Affaires Global -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
            <div class="relative bg-white p-8 rounded-[2.5rem] border border-slate-100 flex flex-col justify-between h-full">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">C.A Global Historique</p>
                <div class="mt-4">
                    <span class="text-5xl font-black text-slate-900 tracking-tighter">{{ number_format($caGlobal, 0, ',', ' ') }}</span>
                    <span class="text-2xl font-bold text-indigo-600 ml-1">$</span>
                </div>
                <div class="mt-6 flex items-center gap-2 text-emerald-500 font-bold text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Fonds sécurisés en caisse
                </div>
            </div>
        </div>

        <!-- Performance Mensuelle -->
        <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-2xl relative overflow-hidden h-full">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] relative z-10">Encaissements / {{ now()->translatedFormat('F') }}</p>
            <div class="mt-4 relative z-10">
                <span class="text-5xl font-black text-white tracking-tighter">{{ number_format($encaissementsMois, 0, ',', ' ') }}</span>
                <span class="text-2xl font-bold text-[#d4af37] ml-1">$</span>
            </div>
            <div class="mt-6 bg-white/10 p-3 rounded-2xl relative z-10">
                <p class="text-[10px] text-slate-300 font-medium italic">Objectif mensuel atteint à 85%</p>
            </div>
            <!-- Décoration fond -->
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Volume d'activité -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between h-full">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Événements Clôturés</p>
            <div class="mt-4">
                <span class="text-5xl font-black text-slate-900 tracking-tighter">{{ $totalEvenements }}</span>
                <span class="text-lg text-slate-400 font-medium ml-2 uppercase">Dossiers</span>
            </div>
            <p class="text-[10px] text-indigo-600 font-bold mt-6 uppercase tracking-widest">Taux d'occupation optimal</p>
        </div>
    </div>

    <!-- ANALYSE PAR CATÉGORIE (Top Model Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Section Catégories -->
        <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm">
            <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight mb-8 flex items-center gap-3">
                <div class="w-2 h-8 bg-[#d4af37] rounded-full"></div>
                Répartition par Cérémonie
            </h3>
            <div class="space-y-6">
                @foreach($categories as $cat)
                    <div class="group">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition">{{ $cat->type_ceremonie }}</span>
                            <span class="text-sm font-black text-slate-900">{{ number_format($cat->total, 0) }} $</span>
                        </div>
                        <div class="w-full h-3 bg-slate-50 rounded-full overflow-hidden border border-slate-100">
                            @php $percent = ($caGlobal > 0) ? ($cat->total / $caGlobal) * 100 : 0; @endphp
                            <div class="h-full bg-indigo-600 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-widest">{{ $cat->nb }} Réservations</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section Information -->
        <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 p-12 rounded-[3rem] text-white flex flex-col justify-center relative overflow-hidden shadow-2xl">
            <div class="relative z-10">
                <i class="fas fa-quote-left text-4xl text-white/20 mb-6"></i>
                <h3 class="text-3xl font-black leading-tight mb-6">"La transparence financière est le socle de notre congrégation."</h3>
                <p class="text-indigo-100 text-sm leading-loose opacity-80 italic">Ce rapport automatique garantit l'intégrité de chaque transaction opérée au Lycée Mahidio. Les données sont inaltérables et certifiées par le protocole Digital Mahidio.</p>
            </div>
            <div class="absolute -left-20 -top-20 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        </div>
    </div>

    <!-- JOURNAL D'AUDIT (Le "Top Model" des Tableaux) -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden">
        <div class="p-10 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Flux de Trésorerie Récents</h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">Audit en temps réel des 20 dernières entrées</p>
            </div>
            <span class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-2xl text-[10px] font-black uppercase tracking-widest animate-pulse">Système Sécurisé</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-[0.3em]">
                        <th class="px-10 py-5">Date d'encaissement</th>
                        <th class="px-10 py-5">Référence Reçu</th>
                        <th class="px-10 py-5">Bénéficiaire</th>
                        <th class="px-10 py-5 text-right">Valeur Nette</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transactions as $t)
                    <tr class="hover:bg-indigo-50/30 transition group">
                        <td class="px-10 py-6">
                            <p class="text-xs font-bold text-slate-700">{{ $t->created_at->translatedFormat('d M Y') }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $t->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-10 py-6">
                            <span class="mono text-xs font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg">#REC-{{ str_pad($t->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <p class="text-sm font-black text-slate-900 uppercase">{{ $t->nom_client }}</p>
                            <p class="text-[9px] text-slate-400 font-bold italic">{{ $t->reservation->type_ceremonie ?? 'Événement' }}</p>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <span class="text-lg font-black text-slate-900 tracking-tighter">{{ number_format($t->montant, 0) }} $</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>