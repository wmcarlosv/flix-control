<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if ($user->is_active) {
                    return redirect()->route('dashboard');
                } else {
                    Auth::logout();
                    return redirect('login')->with('error', 'Su Cuenta se encuentra inactiva, por favor contactar con el administrador del Sistema!!');
                }
            }else{
                return redirect('login')->with('error', 'Las credenciales proporcionadas son invalidas, por favor verifique que el correo y la clave sean los correctos!!');
            }
        }
}
