<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use App\Helpers\Helper;

class Customer extends Model
{
    use HasFactory;

    protected $appends = ['my_subscriptions','my_payments'];

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    protected $table = 'customers';

    public function mySubscriptions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getModalSubscriptions()
        );
    }

    public function payments(){
        return $this->hasMany('App\Models\Payment');
    }

    public function myPayments(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getModalPayments()
        );
    }

    public function subscriptions(){
        return $this->hasMany('App\Models\Subscription');
    }

    public function getModalSubscriptions(){
        $data = "";
        $modal = "-";
        foreach($this->subscriptions as $sub){
            $data.="<tr style='background:white !important;'>";
                $data.="<td>".$sub->service->name."</td>";
                $data.="<td>".$sub->account->email."</td>";
                $data.="<td>".$sub->profile->name."</td>";
                $data.="<td>".$sub->profile->pin."</td>";
                $data.="<td>".date('d-m-Y',strtotime($sub->date_to))."</td>";
                $data.="<td>".$sub->real_status."</td>";
            $data.="</tr>";
        }

        if($this->subscriptions->count() > 0){
            $modal = '<button class="btn btn-success" data-toggle="modal" data-target="#view_customer_'.$this->id.'"><i class="fas fa-star"></i> Ver Lista ('.$this->subscriptions->count().')</button>
            <div class="modal fade" id="view_customer_'.$this->id.'">
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
                                <th>Servicio</th>
                                <th>Cuenta</th>
                                <th>Perfil</th>
                                <th>Pin</th>
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

    public function getModalPayments(){
        $data = "";
        $modal = "-";
        foreach($this->payments as $pay){
            $data.="<tr style='background:white !important;'>";
                $data.="<td>".Helper::currentSymbol()." ".number_format($pay->amount, 2,',','.')."</td>";
                $data.="<td>".date('d-m-Y',strtotime($pay->created_at))."</td>";
            $data.="</tr>";
        }

        if($this->payments->count() > 0){
            $modal = '<button class="btn btn-success" data-toggle="modal" data-target="#view_payment_'.$this->id.'"><i class="fas fa-dollar-sign"></i> Ver Lista ('.$this->payments->count().')</button>
            <div class="modal fade" id="view_payment_'.$this->id.'">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Lista de Pagos Realizados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>Monto</th>
                                <th>Fecha Pago</th>
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
