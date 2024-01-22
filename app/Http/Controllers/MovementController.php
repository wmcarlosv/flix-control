<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
use Session;
use Storage;
use Auth;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Movimientos";

        $columns = [
            [
                'title'=>'Tipo',
                'key'=>'type',
                'type' => 'replace_text',
                'data' => [
                    'input'=>'Entrada',
                    'output'=>'Salida'
                ]
            ],
            [
                'title'=>'Descripción',
                'key'=>'description',
            ],
            [
                'title'=>'Fecha del Movimiento',
                'key'=>'datemovement',
                'type'=>'date',
                'data'=>[
                    'format'=>'d/m/Y'
                ]
            ],
            [
                'title'=>'Monto',
                'key'=>'format_amount'
            ],
        ];

        $data = Movement::all();

        return view('admin.movements.browse', compact('title','columns', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nuevo Movimiento";
        $type = "new";
        return view('admin.movements.add-edit', compact('title','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'=>'required',
            'description'=>'required',
            'datemovement'=>'required',
            'amount'=>'required',

        ]);

        $element = new Movement();
        $element->type = $request->type;
        $element->description = $request->description;
        $element->datemovement = $request->datemovement;
        $element->amount = $request->amount;
        $element->user_id = Auth::user()->id;

        if($element->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('movements.index');
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
        $title = "Editar Movimiento";
        $type = "edit";
        $data = Movement::findorfail($id);
        return view('admin.movements.add-edit', compact('title','type','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type'=>'required',
            'description'=>'required',
            'datemovement'=>'required',
            'amount'=>'required',
        ]);

        $element = Movement::findorfail($id);
        $element->type = $request->type;
        $element->description = $request->description;
        $element->datemovement = $request->datemovement;
        $element->amount = $request->amount;
        $element->user_id = Auth::user()->id;

        if($element->update()){
            Session::flash('success', 'Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratara de Actualizar el Registro!!');
        }

        return redirect()->route('movements.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $element = Movement::findorfail($id);
        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }

        return redirect()->route('movements.index');
    }
}
