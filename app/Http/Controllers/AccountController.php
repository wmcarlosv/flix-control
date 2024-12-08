<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Session;
use Auth;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Movement;
use App\Models\Setting;
use App\Models\Subscription;
use App\Helpers\Helper;
use App\Models\Profile;
use App\Models\User;
use App\Imports\AccountsImport;
use App\Models\Report;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Cuentas";

        $columns = [
            [
                'title'=>'#',
                'key'=>'id'
            ],
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
                'title'=>'Email',
                'key'=>'email'
            ],
            [
                'title'=>'Contraseña',
                'key'=>'password'
            ],
            [
                'title'=>'Facturacion',
                'key'=>'dateto',
                'type'=>'date',
                'data'=>[
                    'format'=>'d/m/Y'
                ]
            ],
            [
                'title'=>'Dias Restantes',
                'key'=>'last_days'
            ],
            [
                'title'=>'Subscripciones',
                'key'=>'the_subscriptions',
                'type'=>'html'
            ],
            [
                'title'=>'Visible en Tienda?',
                'key'=>'is_store',
                'type'=>'replace_text',
                'data'=>[
                    0=>'No',
                    1=>'Si'
                ]
            ],
            [
                'title'=>'Valor de Venta',
                'key'=>'sale_price',
                'type'=>'currency',
                'data'=>[
                    'symbol'=> Helper::currentSymbol()
                ]
            ],
            [
                'title'=>'Precio por Perfil',
                'key'=>'profile_price',
                'type'=>'currency',
                'data'=>[
                    'symbol'=> Helper::currentSymbol()
                ]
            ],
            [
                'title'=>'Reporte',
                'key'=>'get_report_form',
                'type'=>'html'
            ],
            [
                'title'=>'Vencimiento Reseller',
                'key'=>'last_reseller_days'
            ],
            [
                'title'=>'Dueño',
                'key'=>'user_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'user',
                    'key'=>'role_and_name'
                ]
            ]
        ];

        $setting = Setting::first();
        $data = Account::all();
        $customers = Customer::all();
        return view('admin.accounts.browse', compact('title','columns', 'data','customers','setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nueva Cuenta";
        $type = "new";
        $services = Service::all();
        $users = User::where('role','reseller')->get();
        return view('admin.accounts.add-edit', compact('title','type','services','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'dateto'=>'required',
            'amount'=>'required'
        ]);

        $element = new Account();
        $element->email = $request->email;
        $element->passwordemail = $request->passwordemail;
        $element->password = $request->password;
        $element->dateto = $request->dateto;
        $element->service_id = $request->service_id;
        $element->is_store = $request->is_store;
        $element->sale_type = $request->sale_type;
        $element->sale_price = $request->sale_price;
        $element->profile_price = $request->profile_price;

        $element->reseller_due_date = $request->reseller_due_date;

        if(!empty($request->user_id)){
            $element->user_id = $request->user_id;
            $element->sale_type = "complete";
        }else{
            $element->user_id = Auth::user()->id;
        }


        if($element->save()){

            $mvd = [
                'type'=>'output',
                'description'=>'Creacion de Cuenta '.$element->service->name,
                'amount'=>$request->amount
            ];

            Movement::createMovement($mvd);

            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('accounts.edit',$element);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Editar Cuenta";
        $type = "edit";
        $data = Account::findorfail($id);
        $services = Service::all();
        $users = User::where('role','reseller')->get();
        return view('admin.accounts.add-edit', compact('title','type','data','services','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email'=>'required',
            'dateto'=>'required',
        ]);

        $element = Account::findorfail($id);
        $element->email = $request->email;
        $element->passwordemail = $request->passwordemail;
        $element->password = $request->password;
        $element->dateto = $request->dateto;
        $element->service_id = $request->service_id;
        $element->is_store = $request->is_store;
        $element->sale_type = $request->sale_type;
        $element->sale_price = $request->sale_price;
        $element->profile_price = $request->profile_price;
        $element->reseller_due_date = $request->reseller_due_date;
        $element->user_id = $request->user_id;

        if(!empty($request->user_id)){
            $element->user_id = $request->user_id;
        }else{
            $element->user_id = Auth::user()->id;
        }

        if($element->update()){
            Session::flash('success', 'Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratara de Actualizar el Registro!!');
        }

        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $element = Account::findorfail($id);
        Subscription::where('account_id',$id)->delete();
        Profile::where('account_id',$id)->delete();
        Report::where('account_id', $id)->delete();
        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }

        return redirect()->route('accounts.index');
    }

    public function extend_account(Request $request){
        $account = Account::findorfail($request->id); 
        $account->dateto = $request->date_to;

        if($account->update()){

            $mvd = [
                'type'=>'output',
                'description'=>'Se extendio la membresia de la cuenta '.$account->email.' del servicio de '.$account->service->name,
                'amount'=>$request->amount
            ];

            Movement::createMovement($mvd);
            
            $data = [
                'type'=>'success',
                'message'=>'Cuenta Extendida con Exito!!',
                'account'=>$account
            ];
        }else{
            $data = [
                'type'=>'error',
                'message'=>'Ocurrio un error al tratar de extender la Cuenta!!'
            ];
        }

        return response()->json($data);
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csvFile' => 'required|file|mimes:xlsx|max:2048',
        ]);

        $path = $request->file('csvFile')->getRealPath();
        $previewData = [];

        return response()->json([
            'success' => true,
            'data' => $previewData,
        ]);
    }

    public function importAccounts(Request $request){
       
    }
}
