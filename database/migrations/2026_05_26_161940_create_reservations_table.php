<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // IdAffectation
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('nom_agent', 20);
            $table->string('postnom_agent', 20);
            $table->string('matricule_agent', 20);
            $table->date('date_engagement');
            $table->string('type_ceremonie', 50);
            $table->dateTime('date_debut');
            $table->string('duree', 15);
            $table->string('motif', 50)->nullable();
            $table->enum('statut', ['en_attente', 'validee', 'annulee'])->default('en_attente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('reservations'); }
};