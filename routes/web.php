<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;         
use Livewire\Volt\Volt;
use App\Models\Reservation;

// 1. Page d'accueil publique
Route::view('/', 'welcome');

// 2. Logique de Déconnexion
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// 3. Routes protégées (doivent être authentifiées)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard commun
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // --- GROUPE ADMIN ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Volt::route('/users', 'admin.users')->name('users');
    });

    // --- GROUPE RÉSERVISTE ---
    Route::middleware(['role:reserviste'])->prefix('reserviste')->name('reserviste.')->group(function () {
        Volt::route('/reservations', 'reserviste.manage-bookings')->name('bookings');
        Volt::route('/caisse', 'reserviste.cash-register')->name('caisse');
    });

    // --- GROUPE RESPONSABLE ---
    Route::middleware(['role:responsable'])->prefix('responsable')->name('responsable.')->group(function () {
        // C'est cette ligne qui corrige votre erreur :
        Volt::route('/rapports', 'responsable.reports')->name('reports');
    });

    // --- GROUPE CLIENT ---
    Route::middleware(['role:client'])->prefix('client')->name('client.')->group(function () {
        Volt::route('/nouvelle-reservation', 'client.new-booking')->name('new-booking');
        Volt::route('/mes-demandes', 'client.my-bookings')->name('my-bookings');
    });

    // --- IMPRESSION DU REÇU (Commun Staff/Client) ---
    Route::get('/recu/{reservation}', function (Reservation $reservation) {
        if (!$reservation->recu) abort(404);
        return view('receipts.invoice', ['reservation' => $reservation->load('recu', 'client')]);
    })->name('receipt.print');

});

require __DIR__.'/auth.php';