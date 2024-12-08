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
    protected $appends = ['last_days','real_status','share_buttons','get_move_account_form'];

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

    public function getMoveAccountForm(): Attribute{
        return Attribute::make(
            get: fn ($value) => $this->moveAccount()
        );
    }

    public function moveAccount(){
        $modal = '<button type="button" class="btn btn-primary move_account_modal" data-account="'.$this->account_id.'" data-element="'.$this->id.'" data-id="'.$this->service_id.'" data-toggle="modal" data-target="#moveAccountModal-'.$this->id.'">Mover</button>
        <div class="modal fade" id="moveAccountModal-' . $this->id . '" tabindex="-1" aria-labelledby="moveAccountModalLabel-' . $this->id . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moveAccountModalLabel-' . $this->id . '">Mover Cuenta</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="moveAccountForm-' . $this->id . '" action="'.route("accounts.move").'" method="POST">
                        <input type="hidden" name="sup_id" value="'.$this->id.'" />
                            ' . csrf_field() . '
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Cuenta:</label>
                                </div>
                                <div class="col-md-12">
                                <select class="form-control select-profile select-2" style="width:100%;" id="cuenta-' . $this->id . '" name="account_id" required>
                                        <option value="">-</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Perfil:</label>
                                </div>
                                <div class="col-md-12">
                                    <select class="form-control select-2" id="perfil-' . $this->id . '" style="width:100%;" name="profile_id" required>
                                        <option value="">-</option>
                                    </select>
                                </div>
                            </div>
                            <br />
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Mover</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
        return $modal;
    }

}
