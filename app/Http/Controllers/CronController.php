<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;

class CronController extends Controller
{

    private $variables;

    public function __construct(){
        $this->variables = ['#servicio','#cliente','#cuenta','#facturacion','#dias','#perfil','#pin','#clave_cuenta'];
    }

    public function sendMessageExpirateAccount(){
        if(env('APP_TIMEZONE')){
            date_default_timezone_set(env('APP_TIMEZONE'));
        }  
        

        $settings = Setting::first();
        $currentDate = date('Y-m-d');
        if($settings){
            if($settings->isLogged){
                $days = $settings->expiration_days_subscriptions;
                $subscriptions = Subscription::where('date_to','>=', $currentDate)->get();
                foreach($subscriptions as $sub){
                    
                    if( intval($sub->last_days) >= 0 && intval($sub->last_days) <= intval($days) ){

                        if($sub->last_notification_date != $currentDate){

                            if(!empty($sub->customer->phone)){

                                if(empty($settings->hours_range_notification)){
                                    $this->sendMessage($sub->id, $settings->whatsapp_service_url);
                                    sleep(3);
                                }else{
                                    $range = explode("-", $settings->hours_range_notification);
                                    $from = intval(str_replace(":","",$range[0]));
                                    $to = intval(str_replace(":","",$range[1]));
                                    $currentTime = intval(date('Hi'));
                                    if($currentTime >=$from && $currentTime<=$to){
                                        $this->sendMessage($sub->id, $settings->whatsapp_service_url);
                                        sleep(3);
                                    }
                                }

                            }
                        }

                    }

                }
            }
        }
    }

    public function sendMessage($subID, $urlWhatsapp){
        $subscription = Subscription::find($subID);
        $template = $this->getExpirationTemplate($subID);
        if(count($template) > 0){
            if($template['success']){
                $response = Http::post($urlWhatsapp."/send-message", [
                    'number'=>$subscription->customer->phone,
                    'message'=>$template['message']
                ]);
                if($response->status() == 200){
                    $subscription->last_notification_date = date('Y-m-d');
                    $subscription->save();
                }
            }
        }
    }

    public function getExpirationTemplate($id){
        $settings = Setting::first();
        $data = [];
        if($settings){
            $subscription = Subscription::findorfail($id);
            $text = "";
            
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
                                $temporal = str_replace($variable,$subscription->profile->name, $text);
                                $text = $temporal;
                            break;
                            case '#pin':
                                $temporal = str_replace($variable,$subscription->profile->pin, $text);
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
        }

        return $data;
    }
}
