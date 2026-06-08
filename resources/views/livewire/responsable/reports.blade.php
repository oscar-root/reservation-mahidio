<?php
use App\Models\Reservation;
use App\Models\Recu;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{with, layout};

layout('layouts.app');

with(fn () => [
    // 1. Chiffre d'affaires total
    'caTotal' => Recu::sum('montant'),
    
    // 2. Statistiques par type de cérémonie (Page 33 du PDF)
    'statsVentes' => Reservation::where('statut', 'payee')
        ->select('type_ceremonie', DB::raw('count(*) as total_reservations'), DB::raw('sum(montant_total) as CA'))
        ->groupBy('type_ceremonie')
        ->get(),

    // 3. Liste exhaustive pour l'audit (Journal)
    'journalCaisse' => Recu::latest()->get(),
]);
?>

<div class="space-y-10">
    {{-- Header de Gouvernance --}}
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 font-serif-luxury uppercase tracking-tighter">Rapport d'Audit Financier</h1>
            <p class="text-slate-500 text-sm italic">Analyse consolidée des recettes de la Salle Mahidio.</p>
        </div>
        <button onclick="window.print()" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest flex items-center gap-3 shadow-xl hover:bg-slate-800 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimer le rapport
        </button>
    </div>

    {{-- Cartes de Performance --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-indigo-100 relative overflow-hidden">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-2">Chiffre d'Affaires Global</p>
            <p class="text-4xl font-black">{{ number_format($caTotal, 0, ',', ' ') }} $</p>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        @foreach($statsVentes as $stat)
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ $stat->type_ceremonie }}</p>
                <p class="text-2xl font-black text-slate-900">{{ number_format($stat->CA, 0, ',', ' ') }} $</p>
                <p class="text-[10px] text-indigo-600 font-bold mt-2 italic">{{ $stat->total_reservations }} événement(s)</p>
            </div>
        @endforeach
    </div>

    {{-- Journal de Caisse Détaillé (Page 38 - Auditor View) --}}
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest">Journal des encaissements</h3>
            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase">Données Auditées</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">
                        <th class="px-8 py-5">Date & Heure</th>
                        <th class="px-8 py-5">Réf. Dossier</th>
                        <th class="px-8 py-5">Bénéficiaire</th>
                        <th class="px-8 py-5 text-right">Montant Encaissé</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($journalCaisse as $recu)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                                {{ $recu->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-mono text-xs font-bold text-indigo-600">#RES-{{ $recu->reservation_id }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-900">{{ $recu->nom_client }}</p>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-slate-900">
                                {{ number_format($recu->montant, 0, ',', ' ') }} $
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 italic">Aucune donnée financière enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>