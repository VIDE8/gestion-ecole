<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $fillable = ['nom_classe', 'niveau'];

    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'classes_id');
    }
}
