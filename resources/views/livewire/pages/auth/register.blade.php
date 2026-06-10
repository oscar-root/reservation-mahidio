<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'client'; // Sécurité : Forçage du rôle client

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-[#0a0a0a] p-4 font-['Plus_Jakarta_Sans']">
    <!-- Conteneur Principal -->
    <div class="flex w-full max-w-5xl bg-[#111] rounded-[3rem] overflow-hidden shadow-2xl border border-white/5 min-h-[700px]">
        
        <!-- CÔTÉ GAUCHE : VISUEL & MESSAGE -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80" 
                 class="absolute inset-0 w-full h-full object-cover opacity-50 scale-110 hover:scale-100 transition-transform duration-[5s]" alt="Salle Mahidio">
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
            
            <div class="relative z-10 p-12 mt-auto">
                <div class="w-12 h-12 bg-gradient-to-br from-[#d4af37] to-[#b8960c] rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                    <span class="text-black font-bold text-2xl font-serif">M</span>
                </div>
                <h2 class="text-4xl font-bold text-white leading-tight mb-4 font-serif italic">Rejoignez l'excellence <br><span class="text-[#d4af37]">Mahidio</span></h2>
                <p class="text-gray-300 text-sm leading-relaxed max-w-sm">Créez votre compte client pour accéder au planning en temps réel et organiser vos événements de prestige à Kamina.</p>
            </div>
        </div>

        <!-- CÔTÉ DROIT : FORMULAIRE -->
        <div class="w-full lg:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-black/40 backdrop-blur-xl">
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-3xl font-extrabold text-white tracking-tighter">PRENDRE L'INSCRIPTION</h1>
                <p class="text-gray-500 text-sm mt-2">Devenez membre de la communauté Mahidio</p>
            </div>

            <form wire:submit="register" class="space-y-5">
                <!-- Nom -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#d4af37] mb-2 ml-1">Nom complet</label>
                    <input wire:model="name" type="text" required autofocus
                           class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-600 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all"
                           placeholder="Ex: Oscar Munuku">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 ml-1" />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#d4af37] mb-2 ml-1">Adresse Email</label>
                    <input wire:model="email" type="email" required
                           class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-600 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all"
                           placeholder="oscar@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 ml-1" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#d4af37] mb-2 ml-1">Mot de passe</label>
                        <input wire:model="password" type="password" required
                               class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-600 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 ml-1" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#d4af37] mb-2 ml-1">Confirmation</label>
                        <input wire:model="password_confirmation" type="password" required
                               class="w-full bg-white/5 border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-600 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 ml-1" />
                    </div>
                </div>

                <!-- Bouton Submit -->
                <div class="pt-6">
                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-[#d4af37] to-[#b8960c] text-black font-black uppercase text-xs tracking-widest rounded-2xl hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-[#d4af37]/10">
                        Finaliser l'inscription
                    </button>
                </div>

                <!-- Footer du formulaire -->
                <div class="text-center mt-8">
                    <p class="text-gray-500 text-sm">
                        Déjà inscrit ? 
                        <a href="{{ route('login') }}" class="text-[#d4af37] font-bold hover:underline ml-1" wire:navigate>
                            Connectez-vous ici
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>