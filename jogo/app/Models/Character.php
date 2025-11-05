<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $table = 'characters';

    protected $fillable = [
        'name',
        'level',
        'vida',
        'poder',
        'xp',
        'ataque',
        'defesa',
        'image',
    ];

    protected $casts = [
        'level' => 'integer',
        'vida' => 'integer',
        'poder' => 'integer',
        'xp' => 'integer',
        'ataque' => 'integer',
        'defesa' => 'integer',
    ];
}
