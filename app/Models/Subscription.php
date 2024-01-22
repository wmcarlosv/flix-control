<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $appends = ['last_days'];

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    public function lastDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDays()
        );
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function account(){
        return $this->belongsTo('App\Models\Account');
    }

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }

    public function getDays(){
        $now = time();
        $your_date = strtotime($this->date_to);
        $datediff =  $your_date - $now;
        $total = round($datediff / (60 * 60 * 24));
        if($total == 0){
            $total = 1;
        }

        if($total < 0){
            $total = 0;
        }
        return $total;
    }

}
