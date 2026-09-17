<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementCalendrier extends Model
{
    use HasFactory;
    
    protected $table = 'evenements_calendrier';

    protected $fillable = [
        'titre',
        'date_debut',
        'date_fin',
        'type',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
}
