<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use Session;
use Auth;
use DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Clientes";

        $columns = [
            [
                'title'=>'Nombre',
                'key'=>'name'
            ],
            [
                'title'=>'Email',
                'key'=>'email',
            ],
            [
                'title'=>'Telefono',
                'key'=>'phone'
            ],
            [
                'title'=>'Subscripciones',
                'key'=>'my_subscriptions',
                'type'=>'html'
            ],
            [
                'title'=>'Pagos',
                'key'=>'my_payments',
                'type'=>'html'
            ]
        ];

        $data = Customer::all();

        return view('admin.customers.browse', compact('title','columns', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nuevo Cliente";
        $type = "new";
        return view('admin.customers.add-edit', compact('title','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'phone'=>'required|numeric'
        ]);

        $element = new Customer();
        $element->name = $request->name;
        $element->email = $request->email;
        $element->phone = $request->phone;
        $element->user_id = Auth::user()->id;

        if($element->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('customers.index');
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
        $title = "Editar Cliente";
        $type = "edit";
        $data = Customer::findorfail($id);
        return view('admin.customers.add-edit', compact('title','type','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required',
            'phone'=>'required|numeric'
        ]);

        $element = Customer::findorfail($id);
        $element->name = $request->name;
        $element->email = $request->email;
        $element->phone = $request->phone;

        if($element->update()){
            Session::flash('success', 'Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratara de Actualizar el Registro!!');
        }

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $element = Customer::findorfail($id);
        $payments = Payment::where('customer_id', $id)->get();
        $subscriptions = Subscription::where('customer_id', $id)->get();

        if($payments->count() > 0){
            foreach($payments as $payment){
                $payment->delete();
            }
        }

        if($subscriptions->count() > 0){
            foreach($subscriptions as $subscription){
                $subscription->delete();
            }
        }

        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }

        return redirect()->route('customers.index');
    }
}
