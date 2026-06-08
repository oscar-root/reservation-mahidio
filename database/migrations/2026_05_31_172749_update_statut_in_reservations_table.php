<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // On utilise du SQL brut car modifier un ENUM est délicat en PHP pur
        DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('en_attente', 'validee', 'payee', 'annulee') DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('en_attente', 'validee', 'annulee') DEFAULT 'en_attente'");
    }
};