<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 20);
            $table->string('postnom', 20);
            $table->string('adresse');
            $table->string('telephone');
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('clients'); }
};