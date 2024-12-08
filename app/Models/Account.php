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

    public function reports(){
        return $this->hasMany('App\Models\Report');
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
        // Fetch all reports for the current user
        $reports = $this->reports()->where('user_id', Auth::id())->orderBy('id','DESC')->get();
        $status = "";
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
                          <div class="modal-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                              <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="reports-list-tab" data-toggle="tab" href="#reports-list-'.$this->id.'" role="tab" aria-controls="reports-list-'.$this->id.'" aria-selected="true">Lista de Reportes</a>
                              </li>
                              <li class="nav-item" role="presentation">
                                <a class="nav-link" id="new-report-tab" data-toggle="tab" href="#new-report-'.$this->id.'" role="tab" aria-controls="new-report-'.$this->id.'" aria-selected="false">Nuevo Reporte</a>
                              </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                            
                              <div class="tab-pane fade show active" id="reports-list-'.$this->id.'" role="tabpanel" aria-labelledby="reports-list-tab">
                              <div class="table-responsive">
                                <table class="table table-bordered mt-3" id="reports-table">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>Motivo</th>
                                      <th>Respuesta</th>
                                      <th>Imagen</th>
                                      <th>Estado</th>
                                    </tr>
                                  </thead>
                                  <tbody>';
        
        // Populate the table rows with user reports
        foreach ($reports as $report) {
            $respuesta = "Sin Respuesta.";
            if(!empty($report->messages)){
                $respuesta = $report->messages;
            }
             switch($report->status){
                    case 'pending':
                        $status = "Pendiente";
                    break;

                    case 'in_review':
                        $status = "En Revision";
                    break;

                    case 'closed':
                        $status = "Cerrado";
                    break;
                }

            $modal .= '
                                    <tr data-report-id="'.$report->id.'">
                                      <td>'.$report->id.'</td>
                                      <td class="editable" data-field="about">'.$report->about.'</td>
                                      <td class="editable" data-field="about">'.$respuesta.'</td>
                                      <td>';
            if ($report->image) {
                $modal .= '<img src="'.asset('storage/'.$report->image).'" alt="Report Image" style="width: 100px; height: auto;">';
            } else {
                $modal .= 'No disponible';
            }
            $modal .= '</td>
                                      <td class="editable" data-field="status">'.$status.'</td>
                                    </tr>';
        }

        // Handle empty state
        if ($reports->isEmpty()) {
            $modal .= '<tr><td colspan="5" class="text-center">No hay reportes disponibles.</td></tr>';
        }

        $modal .= '</tbody>
                        </table>
                        </div>
                      </div>
                      <!-- New Report Tab -->
                      <div class="tab-pane fade" id="new-report-'.$this->id.'" role="tabpanel" aria-labelledby="new-report-tab">
                        <form method="POST" action="'.route('add_report').'" enctype="multipart/form-data" class="mt-3">
                          '.csrf_field().'
                          '.method_field('POST').'
                          <input type="hidden" name="account_id" value="'.$this->id.'" />

                          <div class="form-group">
                            <label>Servicio:</label>
                            <input type="text" class="form-control" value="'.$this->service->name.'" readonly />
                          </div>

                          <div class="form-group">
                            <label>Cuenta:</label>
                            <input type="text" class="form-control" value="'.$this->email.'" readonly />
                          </div>
                          
                          <div class="form-group">
                            <label for="about">Motivo</label>
                            <textarea name="about" required id="about" class="form-control" rows="3"></textarea>
                          </div>
                          
                          <div class="form-group">
                            <label for="image">Cargar Imagen Adjunta:</label>
                            <input type="file" name="image" id="image" class="form-control-file" accept="image/*">
                          </div>
                          
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">Enviar Reporte</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>';
        return $modal;
    }
}