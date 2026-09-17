<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evenements_calendrier', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('type', ['vacances', 'examen', 'conseil']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evenements_calendrier');
    }
};
