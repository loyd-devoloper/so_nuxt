<?php

namespace App\Models\Qad;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProgramList extends Model
{
    use HasUuids;
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
