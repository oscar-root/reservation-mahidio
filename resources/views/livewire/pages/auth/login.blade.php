<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use function Livewire\Volt\{form, layout, state};

layout('layouts.guest'); 

form(LoginForm::class);

$login = function () {
    $this->validate();

    // CORRECTION : On utilise authenticate() au lieu de store()
    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>

<div class="min-h-screen flex flex-col md:flex-row bg-[#0a0a0a]">
    
    <!-- CÔTÉ GAUCHE : VISUEL IMMERSIF (Masqué sur mobile) -->
    <div class="hidden md:flex md:w-1/2 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/40 to-transparent z-10"></div>
        <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80" 
             class="absolute inset-0 w-full h-full object-cover transform scale-110" alt="Luxe">
        
        <div class="relative z-20 m-auto text-center px-12">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-[#d4af37]/30 mb-8 backdrop-blur-md">
                <span class="text-[9px] font-black uppercase tracking-[0.4em] text-[#f5e7a3]">Prestige & Traçabilité</span>
            </div>
            <h2 class="text-5xl lg:text-7xl font-serif-luxury text-white font-bold leading-tight mb-6">
                L'Art de <span class="gradient-text italic">recevoir</span>
            </h2>
            <p class="text-gray-300 text-lg font-light tracking-wide max-w-md mx-auto leading-relaxed">
                Accédez à votre espace sécurisé pour orchestrer vos événements les plus mémorables.
            </p>
        </div>
    </div>

    <!-- CÔTÉ DROIT : FORMULAIRE TOTALEMENT CENTRÉ -->
    <div class="flex-1 flex items-center justify-center p-8 sm:p-16 lg:p-24 bg-[#0a0a0a]">
        <div class="w-full max-w-md space-y-12 flex flex-col items-center">
            
            <!-- Logo & Titre (Centrés) -->
            <div class="text-center w-full">
                <a href="/" wire:navigate class="inline-block mb-8">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#d4af37] to-[#b8960c] rounded-3xl flex items-center justify-center shadow-2xl mx-auto">
                        <span class="text-black font-bold text-3xl font-serif-luxury">M</span>
                    </div>
                </a>
                <h1 class="text-3xl font-bold text-white tracking-tight">Bon retour</h1>
                <p class="text-gray-500 mt-2 text-sm italic">Identifiez-vous pour accéder au portail Mahidio</p>
            </div>

            <x-auth-session-status class="w-full text-center" :status="session('status')" />

            <form wire:submit="login" class="w-full space-y-8">
                <!-- Email -->
                <div class="space-y-3 text-center">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Adresse Email</label>
                    <input wire:model="form.email" id="email" type="email" name="email" required autofocus 
                           class="block w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-center placeholder-gray-700 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all"
                           placeholder="exemple@mahidio.cd">
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2 text-center" />
                </div>

                <!-- Password -->
                <div class="space-y-3 text-center">
                    <label class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Mot de passe</label>
                    <input wire:model="form.password" id="password" type="password" name="password" required 
                           class="block w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-center placeholder-gray-700 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all"
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2 text-center" />
                </div>

                <!-- Options Centrées (Remember & Forgot) -->
                <div class="flex flex-col items-center gap-4">
                    <label class="inline-flex items-center cursor-pointer">
                        <input wire:model="form.remember" type="checkbox" class="rounded border-white/10 bg-white/5 text-[#d4af37] focus:ring-[#d4af37]">
                        <span class="ml-3 text-xs text-gray-500">Rester connecté</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-[9px] font-bold text-[#d4af37] uppercase tracking-[0.2em] hover:text-white transition">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- Bouton -->
                <button type="submit" class="w-full py-5 bg-gradient-to-r from-[#d4af37] to-[#b8960c] text-black rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] shadow-xl hover:shadow-[#d4af37]/20 transition-all duration-500">
                    Se connecter
                    <span wire:loading class="ml-2 animate-spin inline-block w-3 h-3 border-2 border-black border-t-transparent rounded-full"></span>
                </button>

                <!-- Inscription -->
                <div class="pt-4 text-center">
                    <p class="text-xs text-gray-600">
                        Nouveau client ? 
                        <a href="{{ route('register') }}" wire:navigate class="text-[#d4af37] font-bold hover:underline ml-1">Demander un accès</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <style>
        .gradient-text {
            background: linear-gradient(135deg, #d4af37 0%, #f5e7a3 50%, #d4af37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</div>