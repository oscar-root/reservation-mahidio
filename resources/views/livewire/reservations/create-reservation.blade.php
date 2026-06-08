<?php
use App\Models\Reservation;
use App\Models\Client;
use function Livewire\Volt\{state, rules};

state([
    'date_debut' => '',
    'type_ceremonie' => '',
    'duree' => 1,
    'montant_total' => 0,
    'nom' => '',
    'postnom' => '',
    'telephone' => '',
    'adresse' => ''
]);

// Logique de calcul du prix automatique (Page 30 du PDF)
$updated = function () {
    $prix_base = 100; // Exemple : 100$ par jour
    $this->montant_total = $this->duree * $prix_base;
};

$submit = function () {
    $this->validate([
        'nom' => 'required',
        'telephone' => 'required',
        'date_debut' => 'required|date|after:today',
        'type_ceremonie' => 'required',
    ]);

    // 1. Créer ou récupérer le client
    $client = Client::firstOrCreate(
        ['telephone' => $this->telephone],
        ['nom' => $this->nom, 'postnom' => $this->postnom, 'adresse' => $this->adresse]
    );

    // 2. Créer la réservation (Page 30 - État "En attente")
    Reservation::create([
        'client_id' => $client->id,
        'nom_agent' => 'Système', 
        'postnom_agent' => 'Web',
        'matricule_agent' => 'WEB-001',
        'date_engagement' => now(),
        'type_ceremonie' => $this->type_ceremonie,
        'date_debut' => $this->date_debut,
        'duree' => $this->duree,
        'statut' => 'en_attente',
        'montant_total' => $this->montant_total,
    ]);

    session()->flash('message', 'Demande de réservation soumise avec succès !');
    return redirect()->to('/dashboard');
};
?>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- INFO & CALENDRIER (Colonne Gauche) -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl">
            <h3 class="text-xl font-bold mb-4">Tarification Salle</h3>
            <p class="text-slate-400 text-sm mb-6">Le coût est calculé automatiquement selon la durée de votre événement.</p>
            <div class="text-4xl font-black text-indigo-400">
                {{ $montant_total }} $
            </div>
            <p class="text-xs text-slate-500 mt-2">Paiement à confirmer auprès du réserviste après validation.</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200">
            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Note importante
            </h4>
            <p class="text-sm text-slate-600 leading-relaxed">
                Votre demande sera placée dans une <span class="font-bold">file d'attente</span>. Le réserviste vérifiera la disponibilité avant validation finale.
            </p>
        </div>
    </div>

    <!-- FORMULAIRE (Colonne Droite) -->
    <form wire:submit="submit" class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 p-10 space-y-8 shadow-sm">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Nouvelle Réservation</h2>
            <p class="text-slate-500 text-sm">Veuillez remplir les détails de votre cérémonie.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Nom du demandeur</label>
                <input wire:model="nom" type="text" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Téléphone</label>
                <input wire:model="telephone" type="text" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Type de cérémonie</label>
                <select wire:model.live="type_ceremonie" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                    <option value="">Choisir...</option>
                    <option value="Mariage">Mariage</option>
                    <option value="Conférence">Conférence</option>
                    <option value="Culte">Culte religieux</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Date de début</label>
                <input wire:model="date_debut" type="date" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-slate-700">Durée (en jours)</label>
                <input wire:model.live="duree" type="number" min="1" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform hover:-translate-y-1">
                Soumettre la demande
            </button>
        </div>
    </form>
</div>