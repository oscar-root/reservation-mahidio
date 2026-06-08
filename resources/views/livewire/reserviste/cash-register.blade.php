<?php
use App\Models\Recu;
use function Livewire\Volt\{with, layout};

layout('layouts.app');

with(fn () => [
    'recettesJour' => Recu::whereDate('created_at', now())->get(),
    'totalJour' => Recu::whereDate('created_at', now())->sum('montant'),
]);
?>

<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-900">Journal de Caisse</h1>
        <div class="bg-indigo-600 px-6 py-3 rounded-2xl text-white shadow-lg shadow-indigo-100">
            <span class="text-[10px] uppercase font-black opacity-80 block">Total Encaissé Aujourd'hui</span>
            <span class="text-2xl font-black">{{ number_format($totalJour, 0, ',', ' ') }} $</span>
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                <tr>
                    <th class="px-8 py-5">Réf. Reçu</th>
                    <th class="px-8 py-5">Bénéficiaire</th>
                    <th class="px-8 py-5">Heure</th>
                    <th class="px-8 py-5 text-right">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recettesJour as $recu)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-5 font-mono text-xs font-bold text-indigo-600">#REC-{{ $recu->id }}</td>
                    <td class="px-8 py-5 text-sm font-bold text-slate-900">{{ $recu->nom_client }}</td>
                    <td class="px-8 py-5 text-xs text-slate-500">{{ $recu->created_at->format('H:i') }}</td>
                    <td class="px-8 py-5 text-right font-black text-slate-900">{{ number_format($recu->montant, 0, ',', ' ') }} $</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">Aucune entrée en caisse pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>