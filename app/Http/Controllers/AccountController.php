<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Session;
use Auth;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Accounts";

        $columns = [
            [
                'title'=>'ID',
                'key'=>'id'
            ],
            [
                'title'=>'Email',
                'key'=>'email'
            ],
            [
                'title'=>'Password Email',
                'key'=>'passwordemail',
            ],
            [
                'title'=>'Password',
                'key'=>'password'
            ],
            [
                'title'=>'Date To',
                'key'=>'dateto'
            ],
            [
                'title'=>'Service',
                'key'=>'service_id'
            ],
            [
                'title'=>'Status',
                'key'=>'status',
                'type'=>'replace_text',
                'data' => [
                    '1'=>'true',
                    '0'=>'false'
                ]
            ]
        ];

        $data = Account::all();

        return view('admin.accounts.browse', compact('title','columns', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "New Account";
        $type = "new";
        return view('admin.accounts.add-edit', compact('title','type'));
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
        $element->status = $request->status;

        $element->user_id = Auth::user()->id;

        if($element->save()){
            Session::flash('success', 'Record Inserted Successfully!!');
        }else{
            Session::flash('error', 'Error Inserting The Record!!');
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
        $title = "Edit Account";
        $type = "edit";
        $data = Account::findorfail($id);
        return view('admin.accounts.add-edit', compact('title','type','data'));
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
        $element->status = $request->status;

        if($element->update()){
            Session::flash('success', 'Record Update Successfully!!');
        }else{
            Session::flash('error', 'Error Updating The Record!!');
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
            Session::flash('success', 'Record Deleted Successfully!!');
        }else{
            Session::flash('error', 'Error Deleting The Record!!');
        }

        return redirect()->route('accounts.index');
    }
}
