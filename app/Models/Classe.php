<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = ['nom_classe', 'niveau', 'enseignant_id'];

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'classes_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function comportements()
    {
        return $this->hasMany(Comportement::class);
    }
}
