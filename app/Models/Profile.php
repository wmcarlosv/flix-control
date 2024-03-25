<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";

    public function account(){
        return $this->belongsTo('App\Model\Account');
    }

    public function subscriptions(){
        return $this->hasMany('App\Models\Subscription');
    }
}
