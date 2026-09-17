<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classe_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('statut', ['present', 'absent', 'retard']);
            $table->boolean('justifie')->default(false);
            $table->text('commentaire')->nullable();
            $table->foreignId('enregistre_par')->constrained('users');
            $table->timestamps();

            $table->unique(['eleve_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
