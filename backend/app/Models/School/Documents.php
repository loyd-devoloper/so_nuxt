<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasUuids;
    protected $fillable = [
        'school_id',
        'application_id',
        'document_name',
        'document_type',
        'document_file',
        'document_expiry_date'
    ];
}
