<?php

namespace App\Models\Qad;

use Illuminate\Database\Eloquent\Model;

class SoNumber extends Model
{
    protected $fillable = [
        'id',
        'previous_issued',
        'latest_issued',
        'year',

    ];
}
