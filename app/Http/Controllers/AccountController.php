<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Session;
use Auth;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Movement;

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
                'title'=>'Perfiles',
                'key'=>'list_profiles',
                'type'=>'html'
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
            ]
        ];

        $data = Account::all();
        $customers = Customer::all();
        return view('admin.accounts.browse', compact('title','columns', 'data','customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nueva Cuenta";
        $type = "new";
        $services = Service::all();
        return view('admin.accounts.add-edit', compact('title','type','services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required',
            'passwordemail'=>'required',
        ]);

        $element = new Account();
        $element->email = $request->email;
        $element->passwordemail = $request->passwordemail;
        $element->password = $request->password;
        $element->dateto = $request->dateto;
        $element->service_id = $request->service_id;

        $element->user_id = Auth::user()->id;

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

        return redirect()->route('accounts.index');
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
        return view('admin.accounts.add-edit', compact('title','type','data','services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email'=>'required',
            'passwordemail'=>'required',
        ]);

        $element = Account::findorfail($id);
        $element->email = $request->email;
        $element->passwordemail = $request->passwordemail;
        $element->password = $request->password;
        $element->dateto = $request->dateto;
        $element->service_id = $request->service_id;

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
}
