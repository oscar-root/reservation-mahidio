<?php
use App\Models\Reservation;
use App\Models\Recu; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{state, with, layout, usesPagination};

usesPagination();
layout('layouts.app');

state([
    'search' => '', 
    'filterStatus' => 'active', 
    'perPage' => 10
]);

with(fn () => [
    'bookings' => Reservation::query()
        ->with('client')
        // 1. Recherche multicritère
        ->when($this->search, function($q) {
            $q->whereHas('client', fn($c) => $q->where('nom', 'like', "%{$this->search}%"))
              ->orWhere('type_ceremonie', 'like', "%{$this->search}%")
              ->orWhere('id', 'like', "%{$this->search}%");
        })
        // 2. Filtrage intelligent
        ->when($this->filterStatus !== 'active', function($q) {
            $q->where('statut', $this->filterStatus);
        }, function($q) {
            $q->whereIn('statut', ['en_attente', 'validee', 'payee']);
        })
        // 3. TRI PRIORITAIRE : "En attente" en premier, puis "Validée", puis "Payée"
        // Ensuite, tri par date de création (le plus ancien en haut pour traiter l'ordre d'arrivée)
        ->orderByRaw("FIELD(statut, 'en_attente', 'validee', 'payee', 'annulee') ASC")
        ->orderBy('created_at', 'asc') 
        ->paginate($this->perPage),

    'stats' => [
        'pending' => Reservation::where('statut', 'en_attente')->count(),
        'validee' => Reservation::where('statut', 'validee')->count(),
        'today_revenue' => Recu::whereDate('created_at', now())->sum('montant'),
    ]
]);

// Actions métier
$validateBooking = function (Reservation $reservation) {
    $reservation->update(['statut' => 'validee', 'nom_agent' => auth()->user()->name]);
    session()->flash('status', 'Réservation validée.');
};

$confirmPayment = function (Reservation $reservation) {
    Recu::create([
        'reservation_id' => $reservation->id,
        'nom_client' => $reservation->client->nom,
        'montant' => $reservation->montant_total,
        'date_paiement' => now(),
    ]);
    $reservation->update(['statut' => 'payee']);
    session()->flash('status', 'Encaissement effectué.');
};

$cancelBooking = function (Reservation $reservation) {
    $reservation->update(['statut' => 'annulee']);
    session()->flash('status', 'Dossier annulé.');
};
?>

<div class="space-y-6">
    
    <!-- HEADER AVEC BADGES DE FLUX -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tighter">REGISTRE DES FLUX</h1>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Salles Mahidio • Console d'Administration</p>
        </div>
        
        <div class="flex gap-3">
            <div class="px-5 py-2 bg-amber-50 border border-amber-100 rounded-xl text-center">
                <span class="text-[9px] font-black text-amber-600 uppercase block">À Traiter</span>
                <span class="text-xl font-black text-amber-700">{{ $stats['pending'] }}</span>
            </div>
            <div class="px-5 py-2 bg-indigo-50 border border-indigo-100 rounded-xl text-center">
                <span class="text-[9px] font-black text-indigo-600 uppercase block">Validés</span>
                <span class="text-xl font-black text-indigo-700">{{ $stats['validee'] }}</span>
            </div>
            <div class="px-5 py-2 bg-emerald-600 rounded-xl text-center shadow-lg shadow-emerald-200">
                <span class="text-[9px] font-black text-emerald-100 uppercase block">Caisse Jour</span>
                <span class="text-xl font-black text-white">{{ number_format($stats['today_revenue'], 0) }} $</span>
            </div>
        </div>
    </div>

    <!-- RECHERCHE & FILTRES -->
    <div class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-1 group">
            <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 group-focus-within:text-indigo-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Rechercher par client ou ID..." 
                   class="w-full pl-14 pr-6 py-4 bg-white border-none rounded-[1.5rem] shadow-sm focus:ring-2 focus:ring-indigo-500 font-medium">
        </div>

        <select wire:model.live="filterStatus" class="bg-white border-none rounded-[1.5rem] shadow-sm px-8 py-4 font-bold text-slate-600 focus:ring-2 focus:ring-indigo-500">
            <option value="active">Vue d'ensemble (Actifs)</option>
            <option value="en_attente">Attente uniquement</option>
            <option value="validee">Validées uniquement</option>
            <option value="payee">Clôturées (Payées)</option>
            <option value="annulee">Archives (Annulées)</option>
        </select>
    </div>

    <!-- TABLEAU PROFESSIONNEL -->
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-[0.2em]">
                    <th class="px-8 py-5">Référence</th>
                    <th class="px-8 py-5">Cérémonie & Client</th>
                    <th class="px-8 py-5">Planning</th>
                    <th class="px-8 py-5 text-right">Finance</th>
                    <th class="px-8 py-5">Statut</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $booking)
                <tr class="group hover:bg-slate-50 transition-colors {{ $booking->statut === 'en_attente' ? 'bg-amber-50/30' : '' }}">
                    
                    {{-- ID avec indicateur de priorité --}}
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-3">
                            @if($booking->statut === 'en_attente')
                                <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                            @endif
                            <span class="font-mono text-xs font-bold text-slate-400">#{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </td>

                    {{-- Client --}}
                    <td class="px-8 py-6">
                        <p class="text-sm font-black text-slate-900 uppercase leading-none">{{ $booking->type_ceremonie }}</p>
                        <p class="text-xs text-indigo-600 font-bold mt-2">{{ $booking->client->nom }}</p>
                    </td>

                    {{-- Planning --}}
                    <td class="px-8 py-6 text-sm">
                        <p class="font-bold text-slate-700">{{ Carbon::parse($booking->date_debut)->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-black uppercase mt-1">{{ $booking->duree }}</p>
                    </td>

                    {{-- Finance --}}
                    <td class="px-8 py-6 text-right">
                        <p class="text-lg font-black text-slate-900">{{ number_format($booking->montant_total, 0) }} $</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">Net à payer</p>
                    </td>

                    {{-- Statut --}}
                    <td class="px-8 py-6">
                        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                            {{ $booking->statut === 'en_attente' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $booking->statut === 'validee' ? 'bg-blue-600 text-white shadow-lg' : '' }}
                            {{ $booking->statut === 'payee' ? 'bg-emerald-500 text-white shadow-md' : '' }}
                            {{ $booking->statut === 'annulee' ? 'bg-slate-100 text-slate-400' : '' }}
                        ">
                            {{ str_replace('_', ' ', $booking->statut) }}
                        </span>
                    </td>

                    {{-- Actions contextuelles --}}
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if($booking->statut === 'en_attente')
                                <button wire:click="validateBooking({{ $booking->id }})" class="p-2 bg-indigo-600 text-white rounded-lg shadow-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            @endif

                            @if($booking->statut === 'validee')
                                <button wire:click="confirmPayment({{ $booking->id }})" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-lg hover:bg-emerald-700 transition">
                                    <i class="fas fa-hand-holding-usd"></i> Encaisser
                                </button>
                            @endif

                            @if($booking->statut === 'payee')
                                <a href="{{ route('receipt.print', $booking->id) }}" target="_blank" class="p-2 bg-slate-800 text-white rounded-lg hover:bg-black transition">
                                    <i class="fas fa-print"></i>
                                </a>
                            @endif

                            @if($booking->statut !== 'annulee' && $booking->statut !== 'payee')
                                <button wire:click="cancelBooking({{ $booking->id }})" wire:confirm="Voulez-vous annuler ce dossier ?" class="p-2 bg-white border border-slate-200 text-slate-400 rounded-lg hover:text-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-8 py-20 text-center text-slate-300 italic font-medium">Aucun mouvement dans le registre.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>