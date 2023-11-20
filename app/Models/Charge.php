<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
