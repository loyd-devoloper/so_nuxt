<?php

namespace App\Models\Qad;

use Illuminate\Database\Eloquent\Model;

class ProgramList extends Model
{
    protected $fillable = [
        'curriculum_id',
        'track',
        'track_key',
        'strand'
    ];

    protected $casts = [
        'strand' => 'array',
    ];
}
