<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function account(){
        return $this->belongsTo('App\Models\Account');
    }

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }
}
