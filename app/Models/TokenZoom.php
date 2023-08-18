<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenZoom extends Model
{
    use HasFactory;

    protected $table = 'token_zooms';

    protected $fillable = [
        'CLIENT_ID_ZOOM',
        'CLIENT_SECRET_ZOOM',
        'access_token',
    ];
}
