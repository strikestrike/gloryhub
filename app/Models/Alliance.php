<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kingdom_id',
        'r5_user_id'
    ];

    // Relationships
    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function r5()
    {
        return $this->belongsTo(User::class, 'r5_user_id');
    }
}
