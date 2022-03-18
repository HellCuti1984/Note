<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $fillable = [
        'name', 'desc', 'favorites', 'created_at', 'updated_at'
    ];    

    protected $hidden = [];
}
