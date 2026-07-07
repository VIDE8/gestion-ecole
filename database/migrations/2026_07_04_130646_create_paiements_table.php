<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant_verse', 10, 2);
            $table->enum('type_frais', ['inscription', 'scolarite']);
            $table->date('date_paiement');
            $table->string('reference_recu')->unique();
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
