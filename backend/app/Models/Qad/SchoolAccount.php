<?php

namespace App\Models\Qad;

use App\Models\School\Documents;
use App\Models\School\ProgramOffered;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\HasApiTokens;

class SchoolAccount extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable,HasUuids;
    protected $fillable = [
        'school_number',
        'sdo_id',
        'school_name',
        'school_address',
        'school_head_name',
        'owner_name',
        'school_contact_number',
        'school_email_address',
        'date_established',
        'admin_first_name',
        'admin_last_name',
        'admin_email_address',
        'admin_contact_number',
        'admin_suffix',
        'admin_middle_name',
        'status',
        'is_first_time_login',
        'password'
    ];
    protected function schoolName(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        )->shouldCache();
    }
    protected function schoolContactNumber(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function schoolAddress(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    protected function schoolEmailAddress(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function adminFirstName(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function adminLastName(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function adminEmailAddress(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function adminContactNumber(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    public function sdoInformation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SdoAccount::class, 'id', 'sdo_id');
    }
    public function programOffered(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProgramOffered::class, 'school_id', 'id');
    }

    public function accountAttachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Documents::class, 'school_id', 'school_number');
    }
}
