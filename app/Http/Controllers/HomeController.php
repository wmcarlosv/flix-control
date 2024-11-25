<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
use App\Models\Subscription;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Config;
use App\Helpers\Helper;
use DB;
use Auth;
use Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
            }/*else{
                foreach($des as $d){
                    $expirations_subscriptions[$index] = $des;
                    $index++;
                }
            }*/

            $index = 0;
            $acc = Account::where('status',1)->get();

            if(isset($setting->expiration_days_accounts) and !empty($setting->expiration_days_accounts)){
                foreach($acc as $a){
                    if($a->last_days <= $setting->expiration_days_accounts){
                        $accounts[$index] = $a;
                        $index++;
                    }
                }
            }/*else{
                foreach($acc as $a){
                    $accounts[$index] = $a;
                    $index++;
                }
            }*/
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

        #dd($expirations_subscriptions);

        return view('admin.dashboard', compact('movements','input','output','balance','expirations_subscriptions','accounts','customers','setting'));
    }

    public function downloadBackup()
    {
        $databaseName = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $backupFile = storage_path("backups/{$databaseName}_" . date('Y-m-d_H-i-s') . '.sql');

        // Create the backups directory if it doesn't exist
        if (!is_dir(storage_path('backups'))) {
            mkdir(storage_path('backups'), 0755, true);
        }

        // Command to execute mysqldump and output to a file
        $process = new Process([
            'mysqldump',
            '--user=' . $username,
            '--password=' . $password,
            '--host=' . $host,
            $databaseName,
            '--result-file=' . $backupFile,
        ]);

        try {
            // Execute the process
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            return response()->json(['error' => 'Backup failed: ' . $exception->getMessage()], 500);
        }

        // Return the file as a download and delete it after sending
        return response()->download($backupFile)->deleteFileAfterSend(true);
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
                            $temporal = str_replace($variable,(!empty($subscription->profile) ? $subscription->profile->name : "Sin Perfil"), $text);
                            $text = $temporal;
                        break;
                        case '#pin':
                            $temporal = str_replace($variable,(!empty($subscription->profile) ? $subscription->profile->pin : "Sin Pin"), $text);
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
                            $temporal = str_replace($variable,(!empty($subscription->profile) ? $subscription->profile->name : "Sin Perfil" ), $text);
                            $text = $temporal;
                        break;
                        case '#pin':
                            $temporal = str_replace($variable,(!empty($subscription->profile) ? $subscription->profile->pin : "Sin Pin" ), $text);
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

    public function my_accounts(Request $request){
        $q = "";

        if(!empty($request->get('q'))){
            $q = $request->get('q');
            $accounts = Account::with(['profiles'])->withoutGlobalScopes()
            ->where('user_id',Auth::user()->id)
            ->where('email','like','%'.$q.'%')
            ->get();
        }else{
            $accounts = Account::with(['profiles'])->withoutGlobalScopes()
            ->where('user_id',Auth::user()->id)
            ->orWhereHas('profiles',function($query){
                $query->where('user_id',Auth::user()->id);
            })->get();
        }

        $setting = $this->getSettings();

        

        return view('admin.my_accounts', compact('accounts', 'setting','q'));
    }

    public function store(){
        $accounts = Account::with(['service','profiles'])->withoutGlobalScopes()->where('sold',0)->where('is_store',1)->get();
        return view('admin.store', compact('accounts'));
    }

    public function buy_account(Request $request){
        $account = Account::withoutGlobalScopes()->find($request->account_id);
        $sale_type = $request->sale_type;
        $total = 0;
        
        if($sale_type == "complete"){
            $total = $account->sale_price;
        }else{
            $total = $request->total;
        }

        $response = Helper::removeCredits(Auth::user(), $total);

        if($response == 1){
            if($sale_type == "complete"){
                $account->user_id = Auth::user()->id;
                $account->sold = 1;
                $date = new \DateTime();
                $date->modify('+1 month');
                $new_date = $date->format('Y-m-d');
                $account->reseller_due_date = $new_date;    
            }else{
                $result =  $this->updateProfiles($request->profile_selected, $account, 1);
                if($result == 0){
                    $account->sold = 1;
                }
            }
            
            if($account->save()){
                $data = [
                    'type'=>'output',
                    'description'=>'Compra de la cuenta '.$account->email.' del servicio de '.$account->service->name,
                    'amount' => $total
                ];
                Movement::createMovement($data);
            }
            Session::flash('success','La cuenta '.$account->email.' del servicio de '.$account->service->name.' fue adquirida de manera Satisfactoria!!');
        }else if($response == 2){
            Session::flash('error','No tienes suficientes creditos para adquirir esta cuenta, por favor recarga mas creditos!!');
        }else{
            Session::flash('error','Ocurrio un error al tratar de adquirir la cuenta, por favor comunicate con el administrador!!');
        }
        return redirect()->route('store');
    }

    public function updateProfiles($profiles, $account, $months, $is_update=false){
        $date = new \DateTime();
        $date->modify('+'.$months.' month');
        $new_date = $date->format('Y-m-d');

        foreach($profiles as $profile){
            $cprofile = Profile::find($profile);
            $cprofile->user_id = Auth::user()->id;

            if($is_update){
                $date = new \DateTime($cprofile->due_date);
                $date->modify('+'.$months.' month');
                $new_date = $date->format('Y-m-d');
            }

            $cprofile->due_date = $new_date;
            $cprofile->save();
        }

        return $account->profilesbuyed->count();
    }

    public function extend_reseller_subscription(Request $request){
        $account = Account::withoutGlobalScopes()->find($request->account_id);
        $total = $request->total;
        $months = $request->months;
        $response = Helper::removeCredits(Auth::user(), $total);
        if($response == 1){
            if($request->sale_type == "complete"){
                $account->user_id = Auth::user()->id;
                $account->sold = 1;
                $date = new \DateTime();
                $date->modify('+'.$months.' month');
                $new_date = $date->format('Y-m-d');
                $account->reseller_due_date = $new_date;
                if($account->save()){
                    $data = [
                        'type'=>'output',
                        'description'=>'Extencion de la cuenta '.$account->email.' del servicio de '.$account->service->name,
                        'amount' => $total
                    ];
                    Movement::createMovement($data);
                }
                Session::flash('success','La cuenta '.$account->email.' del servicio de '.$account->service->name.' fue extendida '.$months.' mas de manera Satisfactoria!!');
            }else{
                $profiles = $request->renove_profile_selected;
                $this->updateProfiles($profiles, $account, $months, true);
                $data = [
                        'type'=>'output',
                        'description'=>'Se extendieron '.count($profiles).' de la cuenta '.$account->email.' del servicio de '.$account->service->name,
                        'amount' => $total
                    ];

                    Movement::createMovement($data);
                    Session::flash('success','La extensión de '.count($profiles).' perfiles de la cuenta '.$account->email.' del servicio de '.$account->service->name.' fue extendida '.$months.' meses mas de manera Satisfactoria!!');
            }
        }else if($response == 2){
            Session::flash('error','No tienes suficientes creditos para extender esta cuenta, por favor recarga mas creditos!!');
        }else{
            Session::flash('error','Ocurrio un error al tratar de extender la cuenta, por favor comunicate con el administrador!!');
        }

        return redirect()->route("my_accounts");
    }
}
