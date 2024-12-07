<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Service;
use App\Models\Movement;
use App\Models\Payment;
use App\Models\Customer;
use Auth;
use Session;
use App\Helpers\Helper;
use App\Models\Profile;
use App\Models\Account;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->get('id');

        $title = "Suscripciones";

        $columns = [
            [
                'title'=>'Servicio',
                'key'=>'service_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'service',
                    'key'=>'image_name',
                    'format'=>'html'
                ]
            ],
            [
                'title'=>'Cuenta',
                'key'=>'account_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'account',
                    'key'=>'email',
                ]
            ],
            [
                'title'=>'Perfil',
                'key'=>'profile_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'profile',
                    'key'=>'name',
                    'default_text'=>"Cuenta Completa"
                ]
            ],
            [
                'title'=>'Vendedor',
                'key'=>'user_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'user',
                    'key'=>'role_and_name',
                    'format'=>'text'
                ]
            ],
            [
                'title'=>'Cliente',
                'key'=>'customer_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'customer',
                    'key'=>'name',
                ]
            ],
            [
                'title'=>'Dias Restantes',
                'key'=>'last_days'
            ],
            [
                'title'=>'Facturacion',
                'key'=>'date_to',
                'type'=>'date',
                 'data'=>[
                    'format'=>'d-m-Y'
                 ] 
            ],
            [
                'title'=>'Enviar Datos',
                'key'=>'share_buttons',
                'type'=>'html'
            ],
            [
                'title'=>'Mover Cuenta',
                'key'=>'get_move_account_form',
                'type'=>'html'
            ],
            [
                'title'=>'Estado',
                'key'=>'real_status',
                'type'=>'html'
            ]
        ];

        $data = Subscription::all();

        if($id){
            $data = Subscription::where('id',$id)->get();
        }

        return view('admin.subscriptions.browse', compact('title','columns', 'data'));
    }
    public function create(Request $request)
    {
        $title = "Nueva Suscripcion";
        $type = "new";
        $services = Service::all();
        $customers = Customer::all();
        $service_id = $request->get('service_id');
        $account_id = $request->get('account_id');
        
        return view('admin.subscriptions.add-edit', compact('title','type','services','customers','service_id','account_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'=>'required',
            'account_id'=>'required',
            'customer_id'=>'required',
            'date_to'=>'required',
            'amount'=>'required'
        ]);

        $element = new Subscription();
        $element->service_id = $request->service_id;
        $element->account_id = $request->account_id;
        $element->customer_id = $request->customer_id;
        $element->profile_id = $request->profile_id;
        $element->date_to = $request->date_to;
        $element->user_id = Auth::user()->id;

        if($element->save()){
            $payment = new Payment();
            $payment->customer_id = $element->customer_id;
            $payment->amount = $request->amount;
            if($payment->save()){
                $mvd = [
                    'type'=>'input',
                    'description'=>'Se Creo la subscripcion al servicio '.$element->service->name." de la cuenta con el correo ".$element->account->email.' para el cliente '.$element->customer->name,
                    'amount'=>$request->amount
                ];
                Movement::createMovement($mvd);
            }
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('subscriptions.index');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id)
    {

    }

    public function destroy(string $id)
    {
        $subscription = Subscription::find($id);
        if($subscription->delete()){
            Session::flash('success','Registro Eliminado con Exito!!');
        }else{
            Session::flash('error','Ocurrio un error al tratar de eliminar al Registro!!');
        }
        return redirect()->back();
    }

    public function getAccounts($service_id){
        $cont=0;
        $data = [];
        $accounts = Account::with(['profiles'])->withoutGlobalScopes()
        ->where('user_id',Auth::user()->id)
        ->orWhereHas('profiles', function($query){
            $query->where('user_id',Auth::user()->id);
        })->where('service_id', $service_id)->get();

        foreach($accounts as $account){
            if($account->service_id == $service_id){
                $data[$cont]['id'] = $account->id;
                $data[$cont]['email'] = $account->email." (".$account->service->name.")";
                $cont++;
            }
        }

        return response()->json(['data'=>$data]);
    }

    public function getProfiles($account_id){
        $data = [];
        $attr = [
            'table'=>'profiles',
            'columns'=>[
                'id',
                'name',
                'pin'
            ],
            'compare'=>'account_id',
            'compare_value'=>$account_id
        ];
        $response = Helper::getDataSelect($attr);
        if(count($response) > 0){
            foreach($response as $res){
                $profile = Profile::find($res->id);
                if($profile->subscriptions->count() == 0){

                    if($profile->user_id == Auth::user()->id){
                        array_push($data, $res);
                    }else{
                        if(Auth::user()->role == "super_admin"){
                            array_push($data, $res);
                        }
                    }
                }
            }
        }

        return response()->json(['data'=>$data]);
    }

    public function extend_subscriptions(Request $request){
        $sub = Subscription::findorfail($request->id);
        $page_from = @$request->page_from;


        $sub->date_to = $request->date_to;
        if($sub->update()){
            $payment = new Payment();
            $payment->customer_id = $sub->customer_id;
            $payment->amount = $request->amount;
            if($payment->save()){
                $mvd = [
                    'type'=>'input',
                    'description'=>'Se extendio la subscripcion al servicio '.$sub->service->name." de la cuenta con el correo ".$sub->account->email.' para el cliente '.$sub->customer->name,
                    'amount'=>$request->amount
                ];
                Movement::createMovement($mvd);
            }
            Session::flash('success','Subscripcion Extendida con Exito!!');
        }else{
            Session::flash('error','Error al extender la Membresia!!');
        }

        if(!empty($page_from)){
            return redirect()->route('dashboard');
        }

        return redirect()->route('subscriptions.index');
    }

    public function moveAccount(Request $request){
        $request->validate([
            'account_id'=>'required'
        ]);

        $subscription = Subscription::find($request->sup_id);
        $subscription->account_id = $request->account_id;
        $subscription->profile_id = $request->profile_id;

        if($subscription->update()){
            Session::flash('success','Suscripcion Movida con Exito!!');
        }else{
            Session::flash('error','Error al mover la Suscripcion!!');
        }

        return redirect()->route('subscriptions.index');
    }
}
