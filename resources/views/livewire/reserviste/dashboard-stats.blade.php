<?php
use App\Models\Reservation;
use function Livewire\Volt\{with};

with(fn () => [
    'attente' => Reservation::where('statut', 'en_attente')->count(),
    'recetteJour' => Reservation::where('statut', 'payee')->whereDate('updated_at', now())->sum('montant_total'),
    'tauxOccupation' => Reservation::where('statut', 'payee')->count(),
]);
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-sans">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <p class="text-xs font-bold text-amber-500 uppercase tracking-widest mb-1">À traiter</p>
        <p class="text-3xl font-black text-slate-900">{{ $attente }} <span class="text-sm font-normal text-slate-400 italic">Demandes</span></p>
    </div>

    <div class="bg-emerald-600 p-6 rounded-3xl shadow-xl shadow-emerald-100 text-white">
        <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-1">Encaissements du jour</p>
        <p class="text-3xl font-black tracking-tighter">{{ number_format($recetteJour, 0, ',', ' ') }} $</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Total Confirmées</p>
        <p class="text-3xl font-black text-slate-900">{{ $tauxOccupation }}</p>
    </div>
</div>