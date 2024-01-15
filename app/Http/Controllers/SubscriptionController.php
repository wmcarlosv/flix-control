<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Movement;
use Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $sub = new Subscription();
        $sub->user_id = Auth::user()->id;
        $sub->service_id = $request->service_id;
        $sub->account_id = $request->account_id;
        $sub->customer_id = $request->customer_id;
        $sub->profile = $request->profile;
        $sub->pin = $request->pin;
        $sub->date_to = $request->date_to;
        if($sub->save()){
            $mvd = [
                'type'=>'input',
                'description'=>'Se creo una nueva subscripcion al servicio '.$sub->service->name." de la cuenta con el correo ".$sub->account->email.' para el cliente '.$sub->customer->name,
                'amount'=>$request->amount
            ];
            Movement::createMovement($mvd);
            $data = [
                'type'=>'success',
                'message'=>'Subscripcion Realizada con Exito',
                'subscription'=>$sub
            ];
        }else{
            $data = [
                'type'=>'error',
                'message'=>'Error al realizar la Subscription!!'
            ];
        }

        return response()->json($data);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
    }

    public function update_data(Request $request)
    {
        $sub = Subscription::findorfail($request->id);

        $sub->customer_id = $request->customer_id;
        $sub->profile = $request->profile;
        $sub->pin = $request->pin;
        if($sub->update()){
            $data = [
                'type'=>'success',
                'message'=>'Subscripcion Actualizada con Exito!!',
                'subscription'=>$sub
            ];
        }else{
            $data = [
                'type'=>'error',
                'message'=>'Error al actualizar la Subscription!!'
            ];
        }

        return response()->json($data);
    }

    public function extends(Request $request){
        $sub = Subscription::findorfail($request->id);
        $sub->date_to = $request->date_to;
        if($sub->update()){

            $mvd = [
                'type'=>'input',
                'description'=>'Se extendio la subscripcion al servicio '.$sub->service->name." de la cuenta con el correo ".$sub->account->email.' para el cliente '.$sub->customer->name,
                'amount'=>$request->amount
            ];

            Movement::createMovement($mvd);

            $data = [
                'type'=>'success',
                'message'=>'Subscripcion Extendida con Exito!!',
                'subscription'=>$sub
            ];
        }else{
            $data = [
                'type'=>'error',
                'message'=>'Error al extender la Membresia!!'
            ];
        }

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
