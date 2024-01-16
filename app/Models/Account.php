<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $appends = ['last_days'];

    public function service(){
        return $this->belongsTo('App\Models\Service');
    }

    public function subscriptions(){
        return $this->hasMany("App\Models\Subscription");
    }

    public function listProfiles(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getAccountList()
        );
    }

    public function lastDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDays()
        );
    }

    public function getAccountList(){
        $subcount = $this->subscriptions;
        #dd($subcount);
        $totals = ($this->service->profiles - $this->subscriptions->count());
        $html = "<div>";

        foreach($subcount as $sa){
            $class = "btn-success";
            if($sa->status == false){
                $class = "btn-danger";
            }
            $html.="<a href='#' class='modal-edit btn ".$class."' id='sub_".$sa->id."' data-subscription='".json_encode($sa)."'><i class='fas fa-user'></i></a> ";
        }

        for($i=0;$i<$totals;$i++){
            $html.="<a href='#' class='modal-new btn btn-info' id='free_".$this->id."_".$i."' data-ids='".$this->service_id.",".$this->id.",".$i."'><i class='fas fa-user'></i></a> ";
        }

        $html.="</div>";

        return $html;
    }

    public function getDays(){
        $now = time();
        $your_date = strtotime($this->dateto);
        $datediff =  $your_date - $now;
        $total = round($datediff / (60 * 60 * 24));
        if($total == 0){
            $total = 0;
        }
        return $total;
    }
}
