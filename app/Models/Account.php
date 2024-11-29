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

    protected $appends = ['last_days','the_subscriptions','last_reseller_days','get_report_form'];

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

    public function lastResellerDays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getDaysReseller()
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

    public function getDaysReseller(){
        if(!empty($this->reseller_due_date)){
            $now = time();
            $your_date = strtotime($this->reseller_due_date);
            $datediff =  $your_date - $now;
            $total = round($datediff / (60 * 60 * 24));
            if($total == 0){
                $total = 0;
            }
            return date('d/m/Y', strtotime($this->reseller_due_date))." (".$total." Dias Restantes)";
        }
        
        return "-";
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

    public function getReportForm(): Attribute{
        return Attribute::make(
            get: fn ($value) => $this->ReportForm()
        );
    }

public function ReportForm(){
    $modal = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reportAccount_'.$this->id.'">Reportar</button>
                <div class="modal fade" id="reportAccount_'.$this->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Reporte de Cuenta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="POST" action="'.route('add_report').'" enctype="multipart/form-data">
                      '.csrf_field().'
                      '.method_field('POST').'
                      <div class="modal-body">
                        <input type="hidden" name="account_id" value="'.$this->id.'" />

                        <div class="form-group">
                          <label>Servicio:</label>
                          <input type="text" class="form-control" value="'.$this->service->name.'" readonly />
                        </div>

                        <div class="form-group">
                          <label>Cuenta:</label>
                          <input type="text" class="form-control" value="'.$this->email.'" readonly />
                        </div>
                        
                        <!-- Motivo Field -->
                        <div class="form-group">
                          <label for="about">Motivo</label>
                          <textarea name="about" required id="about" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <!-- Carga una Imagen Field -->
                        <div class="form-group">
                          <label for="image">Cargar Imagen  Adjunta:</label>
                          <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>';

    return $modal;
}


}
