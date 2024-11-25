<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use Storage;
use Session;

class SettingController extends Controller
{
    public function index(){
        $title = "Configuracion del Sitio";
        $data = Setting::first();
        if($data){
            $data = Setting::first();
        }else{
            $data = [];
        }

        $currencies = [];

        $jsonPath = public_path('json/currencies.json');
        if (File::exists($jsonPath)) {
            $jsonData = File::get($jsonPath);
            $currencies = json_decode($jsonData, true);
        }
        return view('admin.settings', compact('title','data','currencies'));
    }

    public function update(Request $request){
        $data = Setting::first();

        if($data){
            $data = Setting::first();
        }else{
            $data = new Setting();
        }

        $data->title = $request->title;
        $data->about = $request->about;
        if($request->hasFile('logo')){
            if(isset($data->logo) and !empty($data->logo)){
                Storage::delete($data->logo);
            }
            
            $data->logo = $request->logo->store('public/settings');
        }

        if($request->hasFile('cover')){
            if(isset($data->cover) and !empty($data->cover)){
                Storage::delete($data->cover);
            }
            
            $data->cover = $request->cover->store('public/settings');
        }

        $data->expiration_template = $request->expiration_template;
        $data->customer_data_template = $request->customer_data_template;
        $data->expiration_days_subscriptions = $request->expiration_days_subscriptions;
        $data->expiration_days_accounts = $request->expiration_days_accounts;
        $data->whatsapp_service_url = $request->whatsapp_service_url;
        $data->currency = $request->currency;
        $data->disable_s_and_c = $request->disable_s_and_c;
        $data->system_notification = $request->system_notification;
        
        if($request->whatsapp_service_url){
            if($request->time_from && $request->time_to){
                $data->hours_range_notification = $request->time_from."-".$request->time_to;
            }
        }

        $data->enable_notifications = $request->enable_notifications;
        $data->enable_register_form = $request->enable_register_form;

        $data->updated_at = date('Y-m-d H:i:s');
        $data->help_url = $request->help_url;
        $data->allow_reseller_ae_movements = $request->allow_reseller_ae_movements;
        
        if($data->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('settings.index');
    }

    public function whatsapp_logged(Request $request){
        $data = Setting::first();
        $data->isLogged = $request->logged;
        $data->update();
        return response()->json(['success'=>true]);
    }
}
