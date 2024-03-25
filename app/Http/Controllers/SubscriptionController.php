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

class SubscriptionController extends Controller
{
    public function index()
    {
        $title = "Subscripciones";

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
                'title'=>'Estado',
                'key'=>'real_status',
                'type'=>'html'
            ]
        ];

        $data = Subscription::all();

        return view('admin.subscriptions.browse', compact('title','columns', 'data'));
    }
    public function create()
    {
        $title = "Nueva Subscripcion";
        $type = "new";
        $services = Service::all();
        $customers = Customer::all();
        return view('admin.subscriptions.add-edit', compact('title','type','services','customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id'=>'required',
            'account_id'=>'required',
            'customer_id'=>'required',
            'profile_id'=>'required',
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
        return redirect()->route('subscriptions.index');
    }

    public function getAccounts($service_id){
        $data = [];
        $attr = [
            'table'=>'accounts',
            'columns'=>[
                'id',
                'email'
            ],
            'compare'=>'service_id',
            'compare_value'=>$service_id
        ];
        $response = Helper::getDataSelect($attr);
        if($response){
            $data = $response;
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
        if($response){
            foreach($response as $res){
                $profile = Profile::find($res->id);
                if($profile->subscriptions->count() == 0){
                    array_push($data, $res);
                }
            }
        }

        return response()->json(['data'=>$data]);
    }

    public function extend_subscriptions(Request $request){
        $sub = Subscription::findorfail($request->id);
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

        return redirect()->route('subscriptions.index');
    }
}
