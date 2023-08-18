<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistTimes extends Model
{
    use HasFactory;

    protected $table = 'specialist_times';

    protected $fillable = [
        'specialist_id',
        'start_date',
        'end_date',
    ];

    public function specialist()
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }
}
