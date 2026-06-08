<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // On augmente la taille pour accepter les noms longs
            $table->string('nom', 100)->change();
            $table->string('postnom', 100)->change();
            // On s'assure que le téléphone (qui contient l'email) est assez large
            $table->string('telephone', 150)->change(); 
        });

        Schema::table('reservations', function (Blueprint $table) {
            // On fait de même pour la table réservation par sécurité
            $table->string('nom_agent', 100)->change();
            $table->string('postnom_agent', 100)->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('nom', 20)->change();
            $table->string('postnom', 20)->change();
        });
    }
};