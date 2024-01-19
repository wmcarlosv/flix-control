<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
use App\Models\Subscription;
use App\Models\Setting;
use App\Models\Account;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $movements = Movement::orderBy('id','Desc')->limit(100)->get();
        $movements_sum = Movement::all();
        $setting = Setting::first();
        $expirations_subscriptions = null;
        $accounts = null;
        $index = 0;

        if($setting){
            $des = Subscription::where('status',1)->get();
            if(isset($setting->expiration_days_subscriptions) and !empty($setting->expiration_days_subscriptions)){
                foreach($des as $d){
                    if($d->last_days <= $setting->expiration_days_subscriptions){
                        $expirations_subscriptions[$index] = $d;
                        $index++;
                    }
                }
            }else{
                foreach($des as $d){
                    $expirations_subscriptions[$index] = $des;
                    $index++;
                }
            }

            $index = 0;
            $acc = Account::where('status',1)->get();

            if(isset($setting->expiration_days_accounts) and !empty($setting->expiration_days_accounts)){
                foreach($acc as $a){
                    if($a->last_days <= $setting->expiration_days_accounts){
                        $accounts[$index] = $a;
                        $index++;
                    }
                }
            }else{
                foreach($acc as $a){
                    $accounts[$index] = $a;
                    $index++;
                }
            }
        }

        $data = [];
        $input=0;
        $output=0;
        $balance = 0;

        foreach($movements_sum as $mv){
            if($mv->type == 'input'){
                $input+=$mv->amount;
            }else{
                $output+=$mv->amount;
            }
        }

        $balance = ($input-$output);

        return view('admin.dashboard', compact('movements','input','output','balance','expirations_subscriptions','accounts'));
    }
}
