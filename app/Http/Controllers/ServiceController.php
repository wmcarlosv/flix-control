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
        $title = "Services";

        $columns = [
            [
                'title'=>'ID',
                'key'=>'id'
            ],
            [
                'title'=>'Name',
                'key'=>'name'
            ],
            [
                'title'=>'Cover',
                'key'=>'cover',
                'type'=>'img'
            ],
            [
                'title'=>'Profiles',
                'key'=>'profiles'
            ],
            [
                'title'=>'Link',
                'key'=>'link'
            ]
        ];

        $data = Service::all();

        return view('admin.services.browse', compact('title','columns', 'data'));
    }
    public function create()
    {
        $title = "New Service";
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
            Session::flash('success', 'Record Inserted Successfully!!');
        }else{
            Session::flash('error', 'Error Inserting The Record!!');
        }

        return redirect()->route('services.index');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {
        $title = "Edit Services";
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
            Session::flash('success', 'Record Update Successfully!!');
        }else{
            Session::flash('error', 'Error Updating The Record!!');
        }

        return redirect()->route('services.index');
    }

    public function destroy(string $id)
    {
        $element = Service::findorfail($id);
        if($element->delete()){
            Session::flash('success', 'Record Deleted Successfully!!');
        }else{
            Session::flash('error', 'Error Deleting The Record!!');
        }

        return redirect()->route('services.index');
    }

}
