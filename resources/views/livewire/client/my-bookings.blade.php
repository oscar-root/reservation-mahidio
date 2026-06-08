<?php
use App\Models\Reservation;
use App\Models\Recu;
use Illuminate\Support\Facades\DB;
use function Livewire\Volt\{with, layout};

layout('layouts.app');

with(fn () => [
    'myBookings' => Reservation::whereHas('client', function($q) {
            $q->where('telephone', auth()->user()->email);
        })
        ->latest()
        ->get(),
]);
?>

<div wire:poll.5s class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 font-serif-luxury tracking-tight">Mes Demandes</h1>
            <p class="text-slate-500 text-sm italic">Suivi de vos réservations en temps réel.</p>
        </div>
        <a href="{{ route('client.new-booking') }}" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 transition shadow-xl">
            Nouvelle demande
        </a>
    </div>

    {{-- Liste des dossiers --}}
    <div class="grid gap-6">
        @forelse($myBookings as $booking)
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 flex flex-col md:flex-row justify-between items-center shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-6">
                    <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-indigo-600 border border-slate-100">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $booking->type_ceremonie }}</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">
                            {{ \Carbon\Carbon::parse($booking->date_debut)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-10">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Montant</p>
                        <p class="text-xl font-black text-slate-900">
                            {{ $booking->montant_total > 0 ? number_format($booking->montant_total, 0, ',', ' ') . ' $' : 'À définir' }}
                        </p>
                    </div>
                    
                    <div class="flex items-center">
                        @php
                            // Vérification directe en base pour le bouton vert
                            $recuGenere = DB::table('recus')->where('reservation_id', $booking->id)->exists();
                        @endphp

                        @if($booking->statut === 'payee' || $recuGenere)
                            <a href="{{ route('receipt.print', $booking->id) }}" target="_blank" 
                               class="flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition transform hover:-translate-y-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                Télécharger Reçu
                            </a>
                        @else
                            <span class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest
                                {{ $booking->statut === 'en_attente' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' }}">
                                {{ str_replace('_', ' ', $booking->statut) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 p-20 rounded-[3rem] text-center">
                <p class="text-slate-400 font-medium italic tracking-wide">Aucune demande de réservation trouvée pour votre compte.</p>
            </div>
        @endforelse
    </div>
</div>