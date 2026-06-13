<?php
use App\Models\Reservation;
use App\Models\Client;
use Carbon\Carbon;
use function Livewire\Volt\{state, rules, computed, layout};

layout('layouts.app');

state([
    'date_debut' => '',
    'type_ceremonie' => 'Mariage',
    'nombre_jours' => 1, // Nouveau : Nombre de jours
    'motif' => '',
]);

// Table des prix de base (par soirée)
$tarifsBase = [
    'Mariage' => 500,
    'Conférence' => 300,
    'Cérémonie Religieuse' => 200,
    'Réunion Académique' => 150,
];

// Calcul dynamique du prix avec réduction
$simulation = computed(function () use ($tarifsBase) {
    $prixUnitaire = $tarifsBase[$this->type_ceremonie] ?? 100;
    $totalBrut = $prixUnitaire * $this->nombre_jours;
    
    // Logique de réduction : 10% de remise à partir du 2ème jour
    $reduction = $this->nombre_jours > 1 ? ($totalBrut * 0.10) : 0;
    $totalFinal = $totalBrut - $reduction;

    return [
        'unitaire' => $prixUnitaire,
        'brut' => $totalBrut,
        'economie' => $reduction,
        'total' => $totalFinal
    ];
});

rules([
    'date_debut' => 'required|date|after:today',
    'type_ceremonie' => 'required',
    'nombre_jours' => 'required|integer|min:1|max:7',
]);

$isAvailable = computed(function () {
    if (!$this->date_debut) return true;
    return !Reservation::whereDate('date_debut', $this->date_debut)
        ->whereIn('statut', ['validee', 'payee'])
        ->exists();
});

$submit = function () {
    $this->validate();

    if (!$this->isAvailable) {
        session()->flash('error', 'Désolé, la salle est déjà occupée.');
        return;
    }

    $client = Client::firstOrCreate(
        ['telephone' => auth()->user()->email],
        ['nom' => auth()->user()->name, 'postnom' => 'Client', 'adresse' => 'Kamina']
    );

    // On enregistre directement le prix calculé (Page 44)
    Reservation::create([
        'client_id' => $client->id,
        'nom_agent' => 'Système',
        'postnom_agent' => 'Auto',
        'matricule_agent' => 'WEB',
        'date_engagement' => now(),
        'type_ceremonie' => $this->type_ceremonie,
        'date_debut' => $this->date_debut,
        'duree' => $this->nombre_jours . ' jour(s)',
        'motif' => $this->motif,
        'statut' => 'en_attente',
        'montant_total' => $this->simulation['total'] // Prix calculé sauvegardé
    ]);

    return redirect()->route('client.my-bookings')->with('status', 'Demande envoyée avec succès !');
};
?>

<div class="max-w-5xl mx-auto space-y-8">
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="grid md:grid-cols-5">
            
            <!-- Côté Illustration & Simulation (2/5) -->
            <div class="md:col-span-2 bg-slate-900 p-10 text-white flex flex-col justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-8 font-serif-luxury italic">Estimation du coût</h2>
                    
                    <div class="space-y-6">
                        <div class="flex justify-between items-center border-b border-white/10 pb-4">
                            <span class="text-slate-400 text-xs uppercase">Prix / Soirée</span>
                            <span class="text-xl font-bold text-[#d4af37]">{{ $this->simulation['unitaire'] }} $</span>
                        </div>
                        
                        <div class="flex justify-between items-center border-b border-white/10 pb-4">
                            <span class="text-slate-400 text-xs uppercase">Nombre de jours</span>
                            <span class="text-xl font-bold">{{ $nombre_jours }}</span>
                        </div>

                        @if($nombre_jours > 1)
                        <div class="flex justify-between items-center text-emerald-400 italic">
                            <span class="text-xs uppercase">Réduction (Offre spéciale)</span>
                            <span class="text-sm font-bold">- {{ number_format($this->simulation['economie'], 0) }} $</span>
                        </div>
                        @endif

                        <div class="pt-6">
                            <span class="text-slate-400 text-[10px] uppercase tracking-widest block mb-2">Total à payer</span>
                            <span class="text-5xl font-black text-white leading-none">
                                {{ number_format($this->simulation['total'], 0, ',', ' ') }}
                                <span class="text-xl text-[#d4af37]">$</span>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Background decor -->
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>
            </div>

            <!-- Côté Formulaire (3/5) -->
            <form wire:submit.prevent="submit" class="md:col-span-3 p-10 space-y-6">
                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-slate-800">Détails de l'événement</h1>
                    <p class="text-slate-400 text-sm">Remplissez les informations pour réserver votre date.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nature de l'événement</label>
                        <select wire:model.live="type_ceremonie" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 font-bold text-slate-700">
                            <option value="Mariage">Mariage</option>
                            <option value="Conférence">Conférence</option>
                            <option value="Cérémonie Religieuse">Cérémonie Religieuse</option>
                            <option value="Réunion Académique">Réunion Académique</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Durée (Nombre de jours)</label>
                        <input wire:model.live="nombre_jours" type="number" min="1" max="7" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Date du début</label>
                    <input wire:model.live="date_debut" type="date" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 font-bold">
                    <x-input-error :messages="$errors->get('date_debut')" class="mt-2" />
                </div>

                @if($date_debut)
                    <div class="p-4 rounded-2xl {{ $this->isAvailable ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' }} text-xs font-bold transition-all">
                        @if($this->isAvailable)
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                                La Salle Mahidio est disponible pour cette date !
                            </div>
                        @else
                            La salle est déjà réservée pour ce jour. Veuillez choisir une autre date.
                        @endif
                    </div>
                @endif

                <button type="submit" @disabled(!$this->isAvailable) class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirmer la demande de réservation
                </button>
            </form>
        </div>
    </div>
</div>