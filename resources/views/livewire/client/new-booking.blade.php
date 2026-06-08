<?php
use App\Models\Reservation;
use App\Models\Client;
use Carbon\Carbon;
use function Livewire\Volt\{state, rules, computed, layout};

layout('layouts.app');

state([
    'date_debut' => '',
    'type_ceremonie' => 'Mariage',
    'duree' => '1 jour',
    'motif' => '',
]);

rules([
    'date_debut' => 'required|date|after:today',
    'type_ceremonie' => 'required',
    'duree' => 'required',
]);

// Vérification de disponibilité en temps réel
$isAvailable = computed(function () {
    if (!$this->date_debut) return true;
    return !Reservation::whereDate('date_debut', $this->date_debut)
        ->whereIn('statut', ['validee', 'payee'])
        ->exists();
});

$submit = function () {
    $this->validate();

    // On utilise l'email comme "Clé de voûte" du lien User <-> Client
    $client = \App\Models\Client::firstOrCreate(
        ['telephone' => auth()->user()->email], // On stocke l'email dans la colonne telephone
        [
            'nom' => auth()->user()->name,
            'postnom' => 'Client',
            'adresse' => 'Kamina',
            // On peut ajouter un champ 'telephone_reel' plus tard si besoin
        ]
    );

    \App\Models\Reservation::create([
        'client_id' => $client->id,
        'nom_agent' => 'Système',
        'postnom_agent' => 'Mahidio',
        'matricule_agent' => 'AUTO',
        'date_engagement' => now(),
        'type_ceremonie' => $this->type_ceremonie,
        'date_debut' => $this->date_debut,
        'duree' => $this->duree,
        'motif' => $this->motif,
        'statut' => 'en_attente',
        'montant_total' => 0
    ]);

    session()->flash('status', 'Votre demande a été envoyée !');
    return redirect()->route('client.my-bookings');
};
?>

<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-[40px] shadow-sm border border-slate-200 overflow-hidden">
        <div class="grid md:grid-cols-2">
            <!-- Côté Illustration -->
            <div class="bg-indigo-600 p-12 text-white flex flex-col justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-4 italic">Salle Mahidio</h2>
                    <p class="text-indigo-100 leading-relaxed">Réservez un cadre d'exception pour vos événements les plus précieux.</p>
                </div>
                <div class="space-y-4 text-sm font-medium">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">✓</div>
                        <span>Vérification instantanée</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">✓</div>
                        <span>Suivi transparent</span>
                    </div>
                </div>
            </div>

            <!-- Côté Formulaire -->
            <form wire:submit.prevent="submit" class="p-12 space-y-6">
                @if (session('error'))
                    <div class="p-4 bg-red-50 text-red-600 rounded-2xl text-sm font-bold">{{ session('error') }}</div>
                @endif

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 italic uppercase tracking-widest text-[10px]">Type de Cérémonie</label>
                    <select wire:model="type_ceremonie" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 font-medium">
                        <option>Mariage</option>
                        <option>Conférence</option>
                        <option>Cérémonie Religieuse</option>
                        <option>Réunion Académique</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 italic uppercase tracking-widest text-[10px]">Date de l'événement</label>
                    <input wire:model.live="date_debut" type="date" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 font-sans">
                    @error('date_debut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                @if($date_debut)
                    @if($this->isAvailable)
                        <p class="text-emerald-600 text-xs font-bold flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span> Date disponible !
                        </p>
                    @else
                        <p class="text-red-500 text-xs font-bold italic">Indisponible à cette date.</p>
                    @endif
                @endif

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 italic uppercase tracking-widest text-[10px]">Précisions (Optionnel)</label>
                    <textarea wire:model="motif" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 text-sm" rows="3" placeholder="Détails supplémentaires..."></textarea>
                </div>

                <button type="submit" @disabled(!$this->isAvailable) class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-indigo-600 transition disabled:opacity-50 shadow-xl">
                    Envoyer ma demande
                </button>
            </form>
        </div>
    </div>
</div>