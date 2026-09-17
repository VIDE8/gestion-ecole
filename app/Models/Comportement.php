<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comportement extends Model
{
    protected $fillable = [
        'eleve_id', 'classe_id', 'date', 'type', 'description', 'enregistre_par',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}
