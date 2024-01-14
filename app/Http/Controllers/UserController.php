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
                    'super_admin'=>'Super Admin',
                    'admin'=>'Admin'
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
                'title'=>'Expiracion',
                'key'=>'date_to'
            ]
        ];

        $data = User::all();

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
        $element->is_active = $request->is_active;
        $element->date_to = $request->date_to;

        if($element->save()){
            Session::flash('success', 'Record Inserted Successfully!!');
        }else{
            Session::flash('error', 'Error Inserting The Record!!');
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

        if($element->update()){
            Session::flash('success', 'Record Update Successfully!!');
        }else{
            Session::flash('error', 'Error Updating The Record!!');
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
            Session::flash('success', 'Record Deleted Successfully!!');
        }else{
            Session::flash('error', 'Error Deleting The Record!!');
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
            Session::flash('success','Record Insert Successfully!!');
        }else{
            Session::flash('error', 'Error Inserting The Record!!');
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
            Session::flash('success','Password Changed Successfully!!');
        }else{
            Session::flash('error', 'Error Changing The Passsword!!');
        }

        return redirect()->route('profile');
    }
}
