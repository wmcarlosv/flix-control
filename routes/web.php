<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController; 
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\HomeController;
use App\Models\Setting;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$data = Setting::first();


 config()->set('adminlte.title',$data->title ? $data->title: 'Flix Control');
 config()->set('adminlte.logo',$data->title ? $data->title: '<b>Flix</b> Control');

 if(!empty($data->logo)){
    config()->set('adminlte.logo_img',asset(str_replace('public','storage',$data->logo)));
    config()->set('adminlte.auth_logo.img.path',asset(str_replace('public','storage',$data->logo)));
    config()->set('adminlte.auth_logo.img.alt',$data->title ? $data->title: 'Flix Control');
 }

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::group(['prefix'=>'admin', 'middleware'=>['auth']], function(){
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('accounts', AccountController::class);
    Route::post('accounts/extend', [AccountController::class, 'extend_account'])->name('extend_account');
    Route::resource('movements', MovementController::class);
    Route::post('subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::post('subscriptions/update', [SubscriptionController::class, 'update_data'])->name('subscriptions.update_data');
    Route::post('subscriptions/extends', [SubscriptionController::class, 'extends'])->name('subscriptions.extends');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('update-profile', [UserController::class, 'update_profile'])->name('update_profile');
    Route::put('update-password', [UserController::class, 'update_password'])->name('update_password');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('get-expiration-message/{id}', [HomeController::class, 'getExpirationTemplate'])->name('get_expiration_template');
    Route::get('get-data-message/{id}', [HomeController::class, 'getCustomerData'])->name('get_data_message');
});