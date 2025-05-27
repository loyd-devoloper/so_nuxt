<?php

namespace App\Models\Auth;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;


class VerificationCode extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'status',
        'code',
        'verified_at',

    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}
