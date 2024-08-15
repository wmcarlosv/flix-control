<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Setting;
use App\Scopes\ByUserScope;

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

    public function theSubscriptions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getTheSubscriptions()
        );
    }

    public function getTheSubscriptions(){
        $data = "";
        $modal = "-";
        foreach($this->subscriptions as $sub){
            $data.="<tr style='background:white !important;'>";
                $data.="<td>".$sub->customer->name."</td>";
                $data.="<td>".@$sub->profile->name."</td>";
                $data.="<td>".@$sub->profile->pin."</td>";
                $data.="<td>".$sub->last_days."</td>";
                $data.="<td>".date('d-m-Y',strtotime($sub->date_to))."</td>";
                $data.="<td>".$sub->real_status."</td>";
            $data.="</tr>";
        }

        if($this->subscriptions->count() > 0){
            $modal = '<button class="btn btn-success" data-toggle="modal" data-target="#view_profiles_'.$this->id.'"><i class="fas fa-star"></i> Ver Lista ('.$this->subscriptions->count().')</button>
            <div class="modal fade" id="view_profiles_'.$this->id.'">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Lista de Subscripciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>Cliente</th>
                                <th>Perfil</th>
                                <th>Pin</th>
                                <th>Dias Restantes</th>
                                <th>Vencimiento</th>
                                <th>Estado</th>
                            </thead>
                            <tbody>'.$data.'</tbody>
                        </table>
                      </div>
                    </div>
                  </div>
            </div>';
        }

        return $modal;
    }
}
