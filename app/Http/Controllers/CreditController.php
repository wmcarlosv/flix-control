<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credit;
use App\Models\User;
use Session;
use Auth;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Creditos";

        $columns = [
            [
                'title'=>'#',
                'key'=>'id'
            ],
            [
                'title'=>'Usuario',
                'key'=>'user_id',
                'type'=>'relation',
                'data'=>[
                    'relation'=>'user',
                    'key'=>'name'
                ]
            ],
            [
                'title'=>'Monto',
                'key'=>'amount',
            ],
            [
                'title'=>'Comentario',
                'key'=>'comment'
            ],
        ];

        $data = Credit::all();
        return view('admin.credits.browse', compact('title','columns', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nuevo Credito";
        $type = "new";
        $users = User::where('role','reseller')->get();
        return view('admin.credits.add-edit', compact('title','type','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'=>'required',
            'amount'=>'required'
        ]);

        $element = new Credit();
        $element->user_id = $request->user_id;
        $element->amount = $request->amount;
        $element->parent_user_id = Auth::user()->id;
        $element->comment = $request->comment;

        if($element->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('credits.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('credits.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('credits.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /*$element = Credit::findorfail($id);

        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }*/

        return redirect()->route('credits.index');
    }

}
