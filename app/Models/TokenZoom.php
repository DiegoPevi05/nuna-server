<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenZoom extends Model
{
    use HasFactory;

    protected $table = 'token_zooms';

    protected $fillable = [
        'access_token',
    ];
}
