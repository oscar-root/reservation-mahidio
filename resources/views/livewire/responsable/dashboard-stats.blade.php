<?php
use App\Models\Recu;
use App\Models\Reservation;
use function Livewire\Volt\{with};

with(fn () => [
    'recettesMois' => Recu::whereMonth('created_at', now()->month)->sum('montant'),
    'reservationsEnCours' => Reservation::where('statut', 'validee')->count(),
    'tauxReussite' => Reservation::count() > 0 ? round((Reservation::where('statut', 'payee')->count() / Reservation::count()) * 100) : 0,
]);
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-sans">
    <div class="bg-white p-6 rounded-3xl border border-slate-200">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Recettes du mois</p>
        <p class="text-3xl font-black text-emerald-600">{{ number_format($recettesMois, 0, ',', ' ') }} $</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-200">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Attente Paiement</p>
        <p class="text-3xl font-black text-amber-500">{{ $reservationsEnCours }} <span class="text-xs font-normal text-slate-400">Dossiers</span></p>
    </div>

    <div class="bg-slate-900 p-6 rounded-3xl text-white shadow-xl">
        <p class="text-xs font-bold opacity-60 uppercase tracking-widest mb-1">Taux de Conversion</p>
        <p class="text-3xl font-black text-[#d4af37]">{{ $tauxReussite }}%</p>
    </div>
</div>