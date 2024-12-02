<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use App\Models\Setting;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $appends = ['last_days','real_status','share_buttons'];

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

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function realStatus(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getRealStatus()
        );
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function account(){
        return $this->belongsTo('App\Models\Account')->withoutGlobalScopes();
    }

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }

    public function profile(){
        return $this->belongsTo('App\Models\Profile');
    }

    public function getDays(){
        $now = time();
        $your_date = strtotime($this->date_to);
        $datediff =  $your_date - $now;
        $total = round($datediff / (60 * 60 * 24));
        if($total == 0){
            $total = 1;
        }

        if($total == -1){
            $total = 0;
        }
        return $total;
    }

    public function getRealStatus(){

        $setting = Setting::first();

        if($this->status == 0){
            return "<span class='badge badge-danger'>Inactivo</span>";
        }else{
            if($this->last_days > 0 && $this->last_days <= $setting->expiration_days_subscriptions){
                return "<span class='badge badge-warning'>Por Vencer</span>";
            }else{
                return "<span class='badge badge-success'>Activo</span>";
            }
        }
    }

    public function shareButtons(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getShareButtons()
        );
    }

    public function getShareButtons(){
        $buttons = "<a href='#' data-id='".$this->id."' data-phone='".$this->customer->phone."' class='btn btn-success send-by-whatsapp'><i class='fab fa-whatsapp'></i></a> <a href='#' class='btn btn-default copy_button' data-id='".$this->id."' ><i class='fas fa-copy'></i></a>";
        return $buttons;
    }

}
