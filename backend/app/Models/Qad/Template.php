<?php

namespace App\Models\Qad;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Template extends Model
{
    //

    protected $fillable = [
        'file_path',
        'name',
        'type',
        'user_id'
    ];
    public function userInfo():HasOne
    {
        return $this->hasOne(User::class,'id','user_id')->select('id','fname','lname');
    }
}
