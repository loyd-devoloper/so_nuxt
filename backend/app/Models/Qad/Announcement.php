<?php

namespace App\Models\Qad;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Announcement extends Model
{
     protected $fillable = [
        'user_id',
        'type',
        'file',
        'content',
        'expiration_date',
    ];

        public function userInfo():HasOne
    {
        return $this->hasOne(User::class,'id','user_id')->select('id','fname','lname');
    }
}
