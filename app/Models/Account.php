<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use App\Scopes\ByUserScope;
use Auth;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $appends = ['last_days','the_subscriptions'];

    private $settings;

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    public function __construct(){
        $data = Setting::first();
        if($data){
            $this->settings = $data;
        }
        $this->booted();
    }

    public function user(){
        return $this->belongsTo('App\Models\User')->withoutGlobalScopes();;
    }

    public function service(){
        return $this->belongsTo('App\Models\Service')->withoutGlobalScopes();
    }

    public function subscriptions(){
        return $this->hasMany("App\Models\Subscription")->withoutGlobalScopes();;
    }

    public function lastDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDays()
        );
    }

    public function getDays(){
        $now = time();
        $your_date = strtotime($this->dateto);

        if($this->sold){
            $your_date = strtotime($this->reseller_due_date);
        }
        
        $datediff =  $your_date - $now;
        $total = round($datediff / (60 * 60 * 24));
        if($total == 0){
            $total = 0;
        }
        return $total;
    }

    public function profiles(){
        return $this->hasMany('App\Models\Profile')->withoutGlobalScopes();
    }

    public function profilesbuyed(){
        return $this->hasMany('App\Models\Profile')->withoutGlobalScopes()->whereNull('user_id');
    }

    public function profilessaled(){
        return $this->hasMany('App\Models\Profile')->withoutGlobalScopes()->where('user_id',Auth::user()->id);
    }

    public function theSubscriptions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getTheSubscriptions()
        );
    }

    public function getTheSubscriptions(){
        $data = "";
        $total_profiles = $this->service->profiles;
        if($this->subscriptions->count() > 0){
            foreach($this->subscriptions as $sub){
               $data.="<a target='_blank' title='".$sub->customer->name."' href='".url('/admin/subscriptions?id='.$sub->id)."' class='btn btn-success items'><i class='fas fa-user'></i></a>";
               $total_profiles--; 
            }
        }

        for($i=0; $i < $total_profiles;$i++){
            $data.="<a href='".route('subscriptions.create')."?service_id=".$this->service_id."&account_id=".$this->id."' title='Agregar Suscripcion' class='btn btn-info items'><i class='fas fa-user'></i></a>";
        }

        return $data;
    }
}
