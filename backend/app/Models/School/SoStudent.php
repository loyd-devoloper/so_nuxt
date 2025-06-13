<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SoStudent extends Model
{
    use HasUuids;
    protected $fillable = [
        'school_id',
        'so_application_id',
        'curriculum_id',
        'so_number',
        'lrn',
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'email_address',
        'contact_number',
        'birth_date',
        'status',
    ];
}
