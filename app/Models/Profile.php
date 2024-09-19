<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";

    protected $appends = ['last_days'];

    public function account(){
        return $this->belongsTo('App\Model\Account');
    }

    public function subscriptions(){
        return $this->hasMany('App\Models\Subscription');
    }

    public function lastDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDays()
        );
    }

    public function getDays(){
        $now = time();
        $your_date = strtotime($this->due_date);

        $datediff =  $your_date - $now;
        $total = round($datediff / (60 * 60 * 24));
        if($total == 0){
            $total = 0;
        }
        return $total;
    }
}
