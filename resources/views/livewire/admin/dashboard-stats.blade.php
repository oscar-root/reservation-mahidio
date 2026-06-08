<?php
use App\Models\User;
use function Livewire\Volt\{with};

with(fn () => [
    'usersCount' => User::count(),
    'staffCount' => User::whereIn('role', ['admin', 'reserviste', 'responsable'])->count(),
    'clientsCount' => User::where('role', 'client')->count(),
]);
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 font-sans">
    <div class="bg-white p-6 rounded-3xl border border-slate-200">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Base de données</p>
        <p class="text-3xl font-black text-slate-900">{{ $usersCount }} <span class="text-sm font-normal text-slate-400">Comptes</span></p>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-200">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Équipe Staff</p>
        <p class="text-3xl font-black text-indigo-600">{{ $staffCount }} <span class="text-sm font-normal text-slate-400">Actifs</span></p>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-200">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Utilisateurs Clients</p>
        <p class="text-3xl font-black text-slate-900">{{ $clientsCount }}</p>
    </div>
</div>