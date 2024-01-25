<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Session;
use Storage;
use Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $title = "Servicios";

        $columns = [
            [
                'title'=>'Nombre',
                'key'=>'name'
            ],
            [
                'title'=>'Portada',
                'key'=>'cover',
                'type'=>'img'
            ],
            [
                'title'=>'Perfiles Permitidos',
                'key'=>'profiles'
            ],
            [
                'title'=>'Enlace',
                'key'=>'link'
            ],
            [
                'title'=>'Cantida de Cuentas',
                'key'=>'account_count'
            ]
        ];

        $data = Service::all();

        return view('admin.services.browse', compact('title','columns', 'data'));
    }
    public function create()
    {
        $title = "Nuevo Servicio";
        $type = "new";
        return view('admin.services.add-edit', compact('title','type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'profiles'=>'required',
        ]);

        $element = new Service();
        $element->name = $request->name;
        if($request->hasFile("cover")){
            $element->cover = $request->cover->store("public/services/covers");
        }
        $element->profiles = $request->profiles;
        $element->link = $request->link;

        $element->user_id = Auth::user()->id;

        if($element->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('services.index');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {
        $title = "Editar Servicio";
        $type = "edit";
        $data = Service::findorfail($id);
        return view('admin.services.add-edit', compact('title','type','data'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required',
            'profiles'=>'required'
        ]);

        $element = Service::findorfail($id);
        $element->name = $request->name;
        if($request->hasFile("cover")){
            Storage::delete($element->cover);
            $element->cover = $request->cover->store("public/services/covers");
        }
        $element->profiles = $request->profiles;
        $element->link = $request->link;

        if($element->update()){
            Session::flash('success', 'Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratara de Actualizar el Registro!!');
        }

        return redirect()->route('services.index');
    }

    public function destroy(string $id)
    {
        $element = Service::findorfail($id);
        Storage::delete($element->cover);
        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }

        return redirect()->route('services.index');
    }

}
