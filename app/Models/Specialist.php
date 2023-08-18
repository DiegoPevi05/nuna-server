<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Specialist extends Model
{
    use HasFactory;

    protected $table = 'specialists';

    protected $fillable = [
        'user_id',
        'services',
        'address',
        'phone_number',
        'sex',
        'profile_image',
        'type_document',
        'document_id',
        'summary',
        'awards',
        'experiences',
        'educations',
        'evaluated_rate',
        'is_active',
        'birthdate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
