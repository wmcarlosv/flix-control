<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ByUserScope;
use Auth;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected static function booted()
    {
        static::addGlobalScope(new ByUserScope);
    }

    public function imageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => "<img src='".asset(str_replace('public','storage',$this->cover))."' class='img-thumbnail' style='width:75px; height:75px;'> ".$this->name
        );
    }

    public function accounts(){
        return $this->hasMany('App\Models\Account');
    }

    public function accountCount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getAccounts()
        );
    }

    public function getAccounts(){
        $data = "";
        $modal = "-";
        foreach($this->accounts as $acc){
            $data.="<tr style='background:white !important;'>";
                $data.="<td>".$acc->email."</td>";
                $data.="<td>".$acc->subscriptions->count()."</td>";
                $data.="<td>".$acc->last_days."</td>";
                $data.="<td>".date('d-m-Y',strtotime($acc->dateto))."</td>";
            $data.="</tr>";
        }

        if($this->accounts->count() > 0){
            $modal = '<button class="btn btn-success" data-toggle="modal" data-target="#view_accounts_'.$this->id.'"><i class="fas fa-star"></i> Ver Lista ('.$this->accounts->count().')</button>
            <div class="modal fade" id="view_accounts_'.$this->id.'">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Lista de Cuentas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>Email</th>
                                <th>Clientes Activos</th>
                                <th>Dias Restantes</th>
                                <th>Facturacion</th>
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

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
