<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Model;

class ProgramOffered extends Model
{
    protected $fillable = [
        'school_id',
        'track',
        'strand',
        'specialization', // JSON field (array)
        'is_available',
        'is_qad_verified',
    ];

    protected $casts = [
        'specialization' => 'array', // Automatically cast to/from JSON
        'is_available' => 'boolean',
        'is_qad_verified' => 'boolean',
    ];
}
