<?php

namespace App\Models\Qad;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasUuids;
    protected $fillable = [
        'school_year_start',
        'school_year_end',
        'is_open_for_so_application',
        'regional_director',
        'assistant_regional_director',
        'open_date',
        'closing_date',
    ];

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProgramList::class,'curriculum_id','id');
    }
}
