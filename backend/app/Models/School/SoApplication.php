<?php

namespace App\Models\School;

use App\Models\Qad\Curriculum;
use App\Models\Qad\SchoolAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SoApplication extends Model
{
     protected $fillable = [
        'curriculum_id',
        'school_id',
        'applied_track',
        'applied_strand',
        'applied_specialization',
        'status',
        'form_checker',
        'evaluation_checker',
        'review_checker',
        'approve_checker',
        'signatory_id',
        'is_form_checked',
        'is_evaluation_checked',
        'is_review_checked',
        'is_approve_checked',
        'graduation_date',
        'date_granted',
        'signed_so_path',
        'expiry_date',
        'is_resubmit',
    ];

    public function curriculumInfo():HasOne
    {
        return $this->hasOne(Curriculum::class,'id','curriculum_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(SoStudent::class,'so_application_id','id');
    }
     public function schoolInfo(): HasOne
    {
        return $this->hasOne(SchoolAccount::class,'school_number','school_id');
    }
}
