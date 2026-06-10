<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use function Livewire\Volt\{state, with, layout, usesPagination, rules};

usesPagination();
layout('layouts.app');

// États pour la gestion du Modal et du Formulaire
state([
    'search' => '', 
    'showModal' => false,
    'name' => '',
    'email' => '',
    'role' => 'client',
    'password' => ''
]);

// Validation
rules([
    'name' => 'required|min:3',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8',
    'role' => 'required|in:admin,reserviste,responsable,client',
]);

with(fn () => [
    'users' => User::where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->latest()->paginate(10),
]);

// Fonction de sauvegarde
$saveUser = function () {
    $this->validate();

    User::create([
        'name' => $this->name,
        'email' => $this->email,
        'password' => Hash::make($this->password),
        'role' => $this->role,
    ]);

    // Réinitialisation
    $this->reset(['name', 'email', 'password', 'role', 'showModal']);
    
    session()->flash('status', 'Utilisateur créé avec succès !');
};

$deleteUser = function (User $user) {
    if($user->id !== auth()->id()){
        $user->delete();
        session()->flash('status', 'Utilisateur supprimé.');
    }
};

$changeRole = function (User $user, $newRole) {
    $user->update(['role' => $newRole]);
};
?>

<div x-data="{ open: @entangle('showModal') }" class="space-y-6">
    
    <!-- Flash Messages -->
    @if (session('status'))
        <div class="bg-emerald-500 text-white p-4 rounded-2xl shadow-lg shadow-emerald-200 font-bold text-sm animate-bounce">
            {{ session('status') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Gestion des Comptes</h1>
            <p class="text-slate-500 text-sm">Contrôlez les accès et les rôles du personnel Mahidio.</p>
        </div>
        <!-- LE BOUTON : Maintenant il ouvre le modal -->
        <button @click="open = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold transition flex items-center gap-2 shadow-lg shadow-indigo-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nouvel Utilisateur
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/30">
            <div class="relative max-w-sm">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input wire:model.live="search" type="text" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-2xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-sm" placeholder="Rechercher par nom ou email...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-400 text-[11px] uppercase tracking-widest font-bold border-b border-slate-100">
                        <th class="px-8 py-4">Utilisateur</th>
                        <th class="px-8 py-4">Rôle</th>
                        <th class="px-8 py-4">Inscription</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="group hover:bg-slate-50/80 transition" wire:key="{{ $user->id }}">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white font-bold shadow-md">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <select wire:change="changeRole({{ $user->id }}, $event.target.value)" 
                                class="text-[10px] font-black uppercase tracking-tighter py-1 pl-3 pr-8 rounded-lg border-slate-200 focus:ring-indigo-500 {{ $user->role === 'admin' ? 'text-purple-600 bg-purple-50' : 'text-slate-600 bg-slate-50' }}">
                                <option value="client">Client</option>
                                <option value="reserviste">Réserviste</option>
                                <option value="responsable">Responsable</option>
                                <option value="admin">Admin</option>
                            </select>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                            {{ $user->created_at->diffForHumans() }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            @if($user->id !== auth()->id())
                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?" class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-8 py-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- MODAL DE CRÉATION (Haut de gamme) -->
    <div x-show="open" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak>
        
        <div class="bg-white w-full max-w-lg rounded-[32px] shadow-2xl overflow-hidden" @click.away="open = false">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-slate-800">Ajouter un collaborateur</h2>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l18 18"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="saveUser" class="p-8 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nom complet</label>
                    <input wire:model="name" type="text" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 shadow-sm" placeholder="Ex: oscar Munuku">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Adresse Email</label>
                    <input wire:model="email" type="email" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 shadow-sm" placeholder="oscar@mahidio.com">
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rôle</label>
                        <select wire:model="role" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 shadow-sm text-sm">
                            <option value="client">Client</option>
                            <option value="reserviste">Réserviste</option>
                            <option value="responsable">Responsable</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Mot de passe</label>
                        <input wire:model="password" type="password" class="w-full rounded-2xl border-slate-200 focus:ring-indigo-500 shadow-sm">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="open = false" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Annuler</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>