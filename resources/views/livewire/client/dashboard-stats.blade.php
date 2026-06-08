<?php
use function Livewire\Volt\{with};
with(fn () => [
    'mesReservations' => auth()->user()->client ? auth()->user()->client->reservations()->count() : 0,
]);
?>
<div class="grid grid-cols-1 gap-6">
    <div class="bg-indigo-600 p-8 rounded-3xl text-white shadow-xl">
        <p class="text-sm font-medium opacity-80">Mes Réservations</p>
        <p class="text-4xl font-bold">{{ $mesReservations }}</p>
    </div>
</div>