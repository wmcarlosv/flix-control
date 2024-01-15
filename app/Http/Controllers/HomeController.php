<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movement;
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

        return view('admin.dashboard', compact('movements','input','output','balance'));
    }
}
