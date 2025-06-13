<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasUuids;
    public $incrementing = false;

    // Set the key type
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'fname',
        'lname',
        'suffix',
        'fd_code',
        'email',
        'password',
        'esign_path',
        'account_status',

    ];


    protected function fname(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn(?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    protected function lname(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn(?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
