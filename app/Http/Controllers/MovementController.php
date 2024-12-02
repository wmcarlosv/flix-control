<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
use Session;
use Storage;
use Auth;
use App\Models\Setting;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = Setting::first();
        $symbol = "$";

        if(!empty($setting->currency)){
            $currency = json_decode($setting->currency, true);
            $symbol = $currency['symbol'];
        }

        $title = "Movimientos";
        $desde = @$request->get('desde');
        $hasta = @$request->get('hasta');
        $entradas = 0;
        $salidas = 0;
        $total = 0;
        $columns = [
            [
                'title'=>'#',
                'type'=>'check',
                'key'=>'id'
            ],
            [
                'title'=>'ID',
                'key'=>'id'
            ],
            [
                'title'=>'Usuario',
                'key'=>'user_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'user',
                    'key'=>'role_and_name',
                    'format'=>'text'
                ]
            ],
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

        if(!empty($desde) and !empty($hasta)){
            $data = Movement::whereBetween('datemovement', [$desde, $hasta])->get();
        }else{
            $data = Movement::all();
        }


        foreach($data as $d){
            if($d->type == "input"){
                $entradas+=$d->amount;
            }else{
                $salidas+=$d->amount;
            }
        }

        $total = $entradas-$salidas;

        $total = $symbol." ".number_format($total,2,',','.');

        return view('admin.movements.browse', compact('title','columns', 'data','desde','hasta','total'));
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

    public function massive_destroy(Request $request){
        $rows = [];
        if(!empty($request->selected_rows)){
            $rows = explode(',', $request->selected_rows);
            foreach($rows as $row){
                $ref = Movement::find($row);
                $ref->delete();
            }
        }

        Session::flash('success', count($rows).' Registros eliminados con exito!!');

        return redirect()->route('movements.index');  
    }
}
