<?php
use App\Models\Reservation;
use Carbon\Carbon;
use function Livewire\Volt\{state, computed};

state(['check_date' => '']);

$isAvailable = computed(function () {
    if (!$this->check_date) return null;
    
    // Vérifie s'il existe une réservation validée ou payée à cette date
    $exists = Reservation::whereDate('date_debut', $this->check_date)
        ->whereIn('statut', ['validee', 'payee'])
        ->exists();

    return !$exists;
});
?>

<div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
    <div class="relative z-10">
        <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Vérifier une disponibilité
        </h3>
        <p class="text-slate-400 text-sm mb-6">Saisissez une date pour interroger la base de données.</p>

        <div class="space-y-4">
            <input wire:model.live="check_date" type="date" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white focus:ring-indigo-500">

            @if($check_date)
                @if($this->isAvailable)
                    <div class="p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-2xl flex items-center gap-3 animate-pulse">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <p class="text-emerald-400 text-sm font-bold">La salle est LIBRE pour le {{ Carbon::parse($check_date)->format('d/m/Y') }}</p>
                    </div>
                @else
                    <div class="p-4 bg-red-500/20 border border-red-500/50 rounded-2xl flex items-center gap-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <p class="text-red-400 text-sm font-bold">La salle est déjà OCCUPÉE.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>