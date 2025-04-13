<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'castle',
        'range',
        'stables',
        'barracks'
    ];

    public $timestamps = false;
}
