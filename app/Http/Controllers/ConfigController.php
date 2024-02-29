<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Config;
use Session;
use Auth;

class ConfigController extends Controller
{
    public function index(){
        $title = "Configuracion";
        $data = Config::where('user_id',Auth::user()->id)->first();
        return view('admin.config',compact('title','data'));
    }

    public function update(Request $request){
        $conf = Config::where('user_id',Auth::user()->id)->first();
        if(!$conf){
            $conf = new Config();
        }

        $conf->user_id = Auth::user()->id;
        $conf->expiration_template = $request->expiration_template;
        $conf->customer_data_template = $request->customer_data_template;
        $conf->expiration_days_subscriptions = $request->expiration_days_subscriptions;
        $conf->expiration_days_accounts = $request->expiration_days_accounts;

        if($conf->save()){
            Session::flash('success','Configuracion Actualizada con Exito!!');
        }else{
            Session::flash('error','Error al intentar actualizar la Configuracion!!');
        }

        return redirect()->route('config.index');
    }
}
