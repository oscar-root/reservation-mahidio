<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;         
use Livewire\Volt\Volt;
use App\Models\Reservation;
use App\Models\Recu;
use Illuminate\Support\Facades\DB;

// 1. Page d'accueil publique
Route::view('/', 'welcome');

// 2. Logique de Déconnexion Professionnelle
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// 3. Routes protégées (Authentification requise)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Dynamique (s'adapte selon le rôle)
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // --- GROUPE ADMINISTRATEUR (Gouvernance) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Volt::route('/users', 'admin.users')->name('users');
    });

    // --- GROUPE RÉSERVISTE (Opérations & Caisse) ---
    Route::middleware(['role:reserviste'])->prefix('reserviste')->name('reserviste.')->group(function () {
        Volt::route('/reservations', 'reserviste.manage-bookings')->name('bookings');
        Volt::route('/caisse', 'reserviste.cash-register')->name('caisse');
    });

    // --- GROUPE RESPONSABLE (Audit & Performance) ---
    Route::middleware(['role:responsable'])->prefix('responsable')->name('responsable.')->group(function () {
        Volt::route('/rapports', 'responsable.reports')->name('reports');
        
        // Nouvelle Route : Génération du Rapport Officiel A4
        Route::get('/rapport-imprimable', function () {
            $data = [
                'caTotal' => Recu::sum('montant'),
                'categories' => Reservation::where('statut', 'payee')
                    ->select('type_ceremonie', DB::raw('count(*) as nb'), DB::raw('sum(montant_total) as total'))
                    ->groupBy('type_ceremonie')
                    ->orderBy('total', 'desc')
                    ->get(),
                'transactions' => Recu::with('reservation.client')->latest()->get(),
            ];
            return view('reports.print-layout', $data);
        })->name('print-report');
    });

    // --- GROUPE CLIENT (Réservations personnelles) ---
    Route::middleware(['role:client'])->prefix('client')->name('client.')->group(function () {
        Volt::route('/nouvelle-reservation', 'client.new-booking')->name('new-booking');
        Volt::route('/mes-demandes', 'client.my-bookings')->name('my-bookings');
    });

    // --- IMPRESSION DU REÇU INDIVIDUEL (Partagé Staff/Client) ---
    Route::get('/recu/{reservation}', function (Reservation $reservation) {
        if (!$reservation->recu) abort(404);
        return view('receipts.invoice', ['reservation' => $reservation->load('recu', 'client')]);
    })->name('receipt.print');

});

require __DIR__.'/auth.php';