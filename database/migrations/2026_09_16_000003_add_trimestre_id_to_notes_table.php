<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // nullable pour ne pas casser les notes déjà existantes (seed actuel)
            $table->foreignId('trimestre_id')->nullable()->after('eleve_id')
                ->constrained('trimestres')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['trimestre_id']);
            $table->dropColumn('trimestre_id');
        });
    }
};
