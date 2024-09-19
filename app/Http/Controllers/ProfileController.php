<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Session;
use App\Models\Account;

class ProfileController extends Controller
{
    public function add_profiles(Request $request){
        $pos = $request->positions;
        $profiles = $request->profiles;
        $pins = $request->pins;
        $account_id = $request->account_id;
        $account = Account::find($account_id);

        $cont = 0;

        foreach($pos as $p){
            if(!empty($profiles[$p])){
                $profile = new Profile();
                $profile->name = $profiles[$p];
                $profile->pin = $pins[$p];
                $profile->account_id = $account_id;
                if($profile->save()){
                    $cont++;
                }
            }
        }

        Session::flash('success','Se agregaron '.$cont." Perfiles de manera Satisfactoria!!");
        return redirect()->route('accounts.edit',$account);
    }

    public function edit_profile(Request $request){
        $profile = Profile::find($request->id);
        $profile->name = $request->name;
        $profile->pin = $request->pin;
        $profile->update();
        return response()->json(['success'=>true]);
    }
}
