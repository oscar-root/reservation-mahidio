<?php
use App\Models\Reservation;
use Carbon\Carbon;
use function Livewire\Volt\{state, computed};

state(['month' => now()->month, 'year' => now()->year]);

// Récupérer les réservations du mois sélectionné
$bookedDates = computed(function () {
    return Reservation::whereMonth('date_debut', $this->month)
        ->whereYear('date_debut', $this->year)
        ->whereIn('statut', ['validee', 'payee'])
        ->get()
        ->groupBy(fn($reg) => Carbon::parse($reg->date_debut)->format('j'));
});

$nextMonth = function () {
    $date = Carbon::create($this->year, $this->month)->addMonth();
    $this->month = $date->month;
    $this->year = $date->year;
};

$prevMonth = function () {
    $date = Carbon::create($this->year, $this->month)->subMonth();
    $this->month = $date->month;
    $this->year = $date->year;
};
?>

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header du Calendrier -->
    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h3 class="text-lg font-bold text-slate-800">
            {{ Carbon::create($year, $month)->translatedFormat('F Y') }}
        </h3>
        <div class="flex gap-2">
            <button wire:click="prevMonth" class="p-2 hover:bg-white rounded-xl border border-slate-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button wire:click="nextMonth" class="p-2 hover:bg-white rounded-xl border border-slate-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>

    <!-- Grille du Calendrier -->
    <div class="p-6">
        <div class="grid grid-cols-7 mb-4">
            @foreach(['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $day)
                <div class="text-center text-[10px] font-black uppercase text-slate-400 tracking-widest">{{ $day }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 gap-2">
            @php
                $startOfMonth = Carbon::create($year, $month)->startOfMonth();
                $endOfMonth = Carbon::create($year, $month)->endOfMonth();
                $daysInMonth = $startOfMonth->daysInMonth;
                $blankDays = ($startOfMonth->dayOfWeek == 0) ? 6 : $startOfMonth->dayOfWeek - 1;
            @endphp

            {{-- Jours vides du début --}}
            @for ($i = 0; $i < $blankDays; $i++)
                <div class="h-24 bg-slate-50/50 rounded-2xl border border-transparent"></div>
            @endfor

            {{-- Jours du mois --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php $isBooked = isset($this->bookedDates[$day]); @endphp
                <div class="h-24 p-3 rounded-2xl border transition relative 
                    {{ $isBooked ? 'border-indigo-200 bg-indigo-50/30' : 'border-slate-100 hover:border-indigo-200' }}">
                    
                    <span class="text-sm font-bold {{ $isBooked ? 'text-indigo-600' : 'text-slate-400' }}">{{ $day }}</span>

                    @if($isBooked)
                        <div class="mt-2 space-y-1">
                            @foreach($this->bookedDates[$day] as $res)
                                <div class="text-[9px] bg-indigo-600 text-white p-1 rounded-lg truncate font-bold" title="{{ $res->type_ceremonie }}">
                                    {{ $res->type_ceremonie }}
                                </div>
                            @endforeach
                        </div>
                        <div class="absolute bottom-2 right-2 w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
    
    <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-6 justify-center text-[10px] font-bold uppercase tracking-widest text-slate-500">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200"></div> Occupé
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-white border border-slate-200 rounded-full"></div> Libre
        </div>
    </div>
</div>