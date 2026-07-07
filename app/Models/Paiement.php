<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory; // Cette ligne manquait pour lier le générateur

    protected $fillable = ['montant_verse', 'type_frais', 'date_paiement', 'reference_recu', 'eleve_id'];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
