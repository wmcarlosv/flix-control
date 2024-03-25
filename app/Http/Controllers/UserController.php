<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Usuarios";

        $columns = [
            [
                'title'=>'Nombre',
                'key'=>'name'
            ],
            [
                'title'=>'Email',
                'key'=>'email'
            ],
            [
                'title'=>'Rol',
                'key'=>'role',
                'type'=>'replace_text',
                'data' => [
                    'super_admin'=>'Super Administrador',
                    'admin'=>'Administrador',
                    'reseller'=>'Vendedor'
                ]
            ],
            [
                'title'=>'Activo',
                'key'=>'is_active',
                'type'=>'replace_text',
                'data' => [
                    '1'=>'Si',
                    '0'=>'No'
                ]
            ],
            [
                'title'=>'Creditos',
                'key'=>'total_credits'
            ]
        ];

        $data = User::byRole()->get();

        return view('admin.users.browse', compact('title','columns', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Nuevo Usuario";
        $type = "new";
        return view('admin.users.add-edit', compact('title','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:4',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|max:16'
        ]);

        $element = new User();
        $element->name = $request->name;
        $element->email = $request->email;
        $element->password = bcrypt($request->password);
        $element->date_to = $request->date_to;
        $element->role = $request->role;
        $element->parent_user_id = Auth::user()->id;

        if($element->save()){
            Session::flash('success', 'Registro Insertado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Editar Usuario";
        $type = "edit";
        $data = User::findorfail($id);
        return view('admin.users.add-edit', compact('title','type','data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'=>'required|min:4',
            'email'=>'required|email|unique:users,email,'.$id,
        ]);

        $element = User::findorfail($id);
        $element->name = $request->name;
        $element->email = $request->email;
        $element->is_active = $request->is_active;
        $element->date_to = $request->date_to;
        $element->role = $request->role;

        if( isset($request->password) and !empty($request->password) ){
            $element->password = bcrypt($request->password);
        }

        if(empty($element->parent_user_id)){
            $element->parent_user_id = Auth::user()->id;
        }

        if($element->update()){
            Session::flash('success', 'Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratara de Actualizar el Registro!!');
        }

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $element = User::findorfail($id);
        if($element->delete()){
            Session::flash('success', 'Registro Eliminado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Eliminar el Registro!!');
        }

        return redirect()->route('users.index');
    }

    public function profile(){
        $title = "Perfil";

        return view('admin.users.profile',compact('title'));
    }

    public function update_profile(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.Auth::user()->id
        ]);

        $user = User::findorfail(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;

        if($user->update()){
            Session::flash('success','Registro Actualizado con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de insertar el Registro!!');
        }

        return redirect()->route('profile');
    }

    public function update_password(Request $request){
        $request->validate([
            'password'=>'required|min:8|max:16|same:password_confirmation',
            'password_confirmation'=>'required|min:8|max:16'
        ]);

        $user = User::findorfail(Auth::user()->id);
        $user->password = bcrypt($request->password);

        if($user->update()){
            Session::flash('success','Contraseña Actualizada con Exito!!');
        }else{
            Session::flash('error', 'Ocurrio un error al tratar de Actualizar la Contraseña!!');
        }

        return redirect()->route('profile');
    }

    public function inactiveUsers(){
        $cont = 0;
        $users = User::where('is_active',1)->where('role','admin')->get();
        foreach($users as $user){
            if($user->last_days == 0){
                $user_update = User::findorfail($user->id);
                $user_update->is_active = 0;
                $user_update->update();
                $cont++;
            }
        }

        print "Se inactivaron ".$cont.', Usuarios ';
    }

    public function activeUsers(){
        $cont = 0;
        $users = User::where('is_active',0)->where('role','admin')->get();
        foreach($users as $user){
            if($user->last_days > 0){
                $user_update = User::findorfail($user->id);
                $user_update->is_active = 1;
                $user_update->update();
                $cont++;
            }
        }

        print "Se activaron ".$cont.', Usuarios ';
    }

    public function cronVerifyUsers(){
        $this->inactiveUsers();
        $this->activeUsers();
    }
}
