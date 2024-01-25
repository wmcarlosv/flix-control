<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
use App\Models\Subscription;
use App\Models\Setting;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Config;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $variables = [];

    public function __construct()
    {
        $this->variables = ['#servicio','#cliente','#cuenta','#facturacion','#dias','#perfil','#pin','#clave_cuenta'];
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $movements = Movement::orderBy('id','Desc')->limit(100)->get();
        $movements_sum = Movement::all();
        $setting = $this->getSettings();
        $customers = Customer::all();
        $expirations_subscriptions = null;
        $accounts = null;
        $index = 0;

        if($setting){
            $des = Subscription::where('status',1)->get();
            if(isset($setting->expiration_days_subscriptions) and !empty($setting->expiration_days_subscriptions)){
                foreach($des as $d){
                    if($d->last_days <= $setting->expiration_days_subscriptions){
                        $expirations_subscriptions[$index] = $d;
                        $index++;
                    }
                }
            }else{
                foreach($des as $d){
                    $expirations_subscriptions[$index] = $des;
                    $index++;
                }
            }

            $index = 0;
            $acc = Account::where('status',1)->get();

            if(isset($setting->expiration_days_accounts) and !empty($setting->expiration_days_accounts)){
                foreach($acc as $a){
                    if($a->last_days <= $setting->expiration_days_accounts){
                        $accounts[$index] = $a;
                        $index++;
                    }
                }
            }else{
                foreach($acc as $a){
                    $accounts[$index] = $a;
                    $index++;
                }
            }
        }

        $data = [];
        $input=0;
        $output=0;
        $balance = 0;

        foreach($movements_sum as $mv){
            if($mv->type == 'input'){
                $input+=$mv->amount;
            }else{
                $output+=$mv->amount;
            }
        }

        $balance = ($input-$output);

        return view('admin.dashboard', compact('movements','input','output','balance','expirations_subscriptions','accounts','customers'));
    }

    public function getExpirationTemplate($id){
        $subscription = Subscription::findorfail($id);
        $settings = $this->getSettings();
        $text = "";
        $data = [];
        if($settings){
            if($settings->expiration_template){
                $text = $settings->expiration_template;
                foreach($this->variables as $variable){
                    $temporal = "";
                    switch($variable){
                        case '#servicio':
                            $temporal = str_replace($variable,$subscription->service->name, $text);
                            $text = $temporal;
                        break;
                        case '#cliente':
                            $temporal = str_replace($variable,$subscription->customer->name, $text);
                            $text = $temporal;
                        break;
                        case '#cuenta':
                            $temporal = str_replace($variable,$subscription->account->email, $text);
                            $text = $temporal;
                        break;
                        case '#facturacion':
                            $temporal = str_replace($variable,date('d/m/Y',strtotime($subscription->date_to)), $text);
                            $text = $temporal;
                        break;
                        case '#dias':
                            $temporal = str_replace($variable,$subscription->last_days, $text);
                            $text = $temporal;
                        break;
                        case '#perfil':
                            $temporal = str_replace($variable,$subscription->profile, $text);
                            $text = $temporal;
                        break;
                        case '#pin':
                            $temporal = str_replace($variable,$subscription->pin, $text);
                            $text = $temporal;
                        break;
                        case '#clave_cuenta':
                            $temporal = str_replace($variable,$subscription->account->password, $text);
                            $text = $temporal;
                        break;
                    }
                }
                $data = [
                    'success'=>true,
                    'message'=>$text
                ];
            }else{
                $data = [
                    'success'=>false,
                    'message'=>'El template de expiracion esta vacio, debes agregar el mensaje de expiracion de subscripcion!!'
                ];
            }
        }else{
            $data = [
                'success'=>false,
                'message'=>'no tienes configuracion disponile, por favor ve al menu de confiuracion e ingresala!!'
            ];
        }

        return response()->json($data);
    }

    public function getCustomerData($id){
        $subscription = Subscription::findorfail($id);
        $settings = $this->getSettings();
        $text = "";
        $data = [];
        if($settings){
            if($settings->customer_data_template){
                $text = $settings->customer_data_template;
                foreach($this->variables as $variable){
                    $temporal = "";
                    switch($variable){
                        case '#servicio':
                            $temporal = str_replace($variable,$subscription->service->name, $text);
                            $text = $temporal;
                        break;
                        case '#cliente':
                            $temporal = str_replace($variable,$subscription->customer->name, $text);
                            $text = $temporal;
                        break;
                        case '#cuenta':
                            $temporal = str_replace($variable,$subscription->account->email, $text);
                            $text = $temporal;
                        break;
                        case '#facturacion':
                            $temporal = str_replace($variable,date('d/m/Y',strtotime($subscription->date_to)), $text);
                            $text = $temporal;
                        break;
                        case '#dias':
                            $temporal = str_replace($variable,$subscription->last_days, $text);
                            $text = $temporal;
                        break;
                        case '#perfil':
                            $temporal = str_replace($variable,$subscription->profile, $text);
                            $text = $temporal;
                        break;
                        case '#pin':
                            $temporal = str_replace($variable,$subscription->pin, $text);
                            $text = $temporal;
                        break;
                        case '#clave_cuenta':
                            $temporal = str_replace($variable,$subscription->account->password, $text);
                            $text = $temporal;
                        break;
                    }
                }
                $data = [
                    'success'=>true,
                    'message'=>$text
                ];
            }else{
                $data = [
                    'success'=>false,
                    'message'=>'El template de datos de cliente esta vacio, debes agregar el mensaje de datos del cliente!!'
                ];
            }
        }else{
            $data = [
                'success'=>false,
                'message'=>'no tienes configuracion disponile, por favor ve al menu de confiuracion e ingresala!!'
            ];
        }

        return response()->json($data);
    }

    public function getSettings(){
        $setting = Config::where('user_id',Auth::user()->id)->first();
        if(!$setting){
            $setting = Setting::first();
        }

        return $setting;
    }
}
