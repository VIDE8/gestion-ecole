<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = ['nom', 'prenom', 'date_naissance', 'matricule', 'classes_id'];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
