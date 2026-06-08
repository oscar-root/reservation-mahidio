<?php
use App\Models\Reservation;
use App\Models\Recu; 
use function Livewire\Volt\{state, with, layout, usesPagination};

usesPagination();
layout('layouts.app');

state(['search' => '', 'filter' => 'tous']);

with(fn () => [
    'bookings' => Reservation::query()
        ->when($this->filter !== 'tous', fn($q) => $q->where('statut', $this->filter))
        ->whereHas('client', fn($q) => $q->where('nom', 'like', "%{$this->search}%"))
        ->latest()
        ->paginate(10),
]);

$validateBooking = function (Reservation $reservation) {
    $tarifs = [
        'Mariage' => 500,
        'Conférence' => 300,
        'Cérémonie Religieuse' => 200,
        'Réunion Académique' => 150,
    ];
    $prix = $tarifs[$reservation->type_ceremonie] ?? 100;

    $reservation->update([
        'statut' => 'validee',
        'montant_total' => $prix,
        'nom_agent' => auth()->user()->name,
    ]);
    session()->flash('status', "Dossier validé au tarif de $prix $");
};

$confirmPayment = function (Reservation $reservation) {
    if($reservation->statut === 'validee') {
        Recu::create([
            'reservation_id' => $reservation->id,
            'nom_client' => $reservation->client->nom,
            'montant' => $reservation->montant_total,
            'date_paiement' => now(),
        ]);
        $reservation->update(['statut' => 'payee']);
        session()->flash('status', 'Paiement confirmé et reçu généré.');
    }
};

$cancelBooking = function (Reservation $reservation) {
    $reservation->update(['statut' => 'annulee']);
};
?>

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-900">Registre Opérationnel</h1>
        <select wire:model.live="filter" class="rounded-xl border-slate-200 text-sm">
            <option value="tous">Tous les dossiers</option>
            <option value="en_attente">En attente</option>
            <option value="validee">Prêt au paiement</option>
            <option value="payee">Payées / Clôturées</option>
        </select>
    </div>

    @if (session('status'))
        <div class="bg-indigo-600 text-white p-4 rounded-2xl shadow-lg font-bold text-sm">{{ session('status') }}</div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Cérémonie</th>
                    <th class="px-8 py-4 text-center">Montant</th>
                    <th class="px-8 py-4">État</th>
                    <th class="px-8 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($bookings as $booking)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-5">
                        <p class="text-sm font-bold text-slate-900">{{ $booking->type_ceremonie }}</p>
                        <p class="text-[10px] text-indigo-600 font-bold">Client: {{ $booking->client->nom }}</p>
                    </td>
                    <td class="px-8 py-5 text-center font-bold">
                        {{ $booking->montant_total > 0 ? number_format($booking->montant_total, 0, ',', ' ') . ' $' : '---' }}
                    </td>
                    <td class="px-8 py-5">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                            {{ $booking->statut === 'en_attente' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $booking->statut === 'validee' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $booking->statut === 'payee' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        ">{{ $booking->statut }}</span>
                    </td>
                    <td class="px-8 py-5 text-right">
                        @if($booking->statut === 'en_attente')
                            <button wire:click="validateBooking({{ $booking->id }})" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition">Valider</button>
                        @endif
                        @if($booking->statut === 'validee')
                            <button wire:click="confirmPayment({{ $booking->id }})" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-emerald-700 transition">Confirmer Paiement</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>