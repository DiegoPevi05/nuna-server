<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetHistory extends Model
{
    use HasFactory;

    protected $table = 'meet_histories';

    protected $fillable = [
        'meet_id',
    ];

    public function meet()
    {
        return $this->belongsTo(Meet::class, 'meet_id');
    }
}
