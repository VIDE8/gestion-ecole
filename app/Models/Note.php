<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['valeur', 'matiere', 'eleve_id'];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
}
