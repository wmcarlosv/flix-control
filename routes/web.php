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
use App\Http\Controllers\ConfigController;
use App\Models\Setting;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;



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

if(env('APP_ENV') == 'production'){

    $data = Setting::first();
     config()->set('adminlte.title',$data->title ? $data->title: 'Flix Control');
     config()->set('adminlte.logo',$data->title ? $data->title: '<b>Flix</b> Control');

     if(!empty($data->logo)){
        config()->set('adminlte.logo_img',asset(str_replace('public','storage',$data->logo)));
        config()->set('adminlte.auth_logo.img.path',asset(str_replace('public','storage',$data->logo)));
        config()->set('adminlte.auth_logo.img.alt',$data->title ? $data->title: 'Flix Control');
     }
}

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/cron',[CronController::class, 'sendMessageExpirateAccount']);

if (config('app.debug')) {
    Route::get('/dev/{command}', function ($command) {
        Artisan::call($command);
        $output = Artisan::output();
        dd($output);
    });
}

Route::get('cron/verify-users', [UserController::class, 'cronVerifyUsers']);

Auth::routes();

Route::group(['prefix'=>'admin', 'middleware'=>['auth']], function(){
    
    Route::get('/', function(){ return redirect()->route('dashboard'); });

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->middleware('can:isSuperAdmin');
    Route::resource('services', ServiceController::class)->middleware('can:isSuperAdmin');
    Route::resource('customers', CustomerController::class);
    Route::resource('accounts', AccountController::class)->middleware('can:isSuperAdmin');
    Route::post('accounts/extend', [AccountController::class, 'extend_account'])->name('extend_account');
    Route::resource('movements', MovementController::class);
    Route::post('movements-delete-massive', [MovementController::class, 'massive_destroy'])->name('movements.delete_massive');
    Route::resource('credits',CreditController::class)->middleware('can:isSuperAdmin');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::put('update-profile', [UserController::class, 'update_profile'])->name('update_profile');
    Route::put('update-password', [UserController::class, 'update_password'])->name('update_password');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index')->middleware('can:isSuperAdmin');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('can:isSuperAdmin');
    Route::get('get-expiration-message/{id}', [HomeController::class, 'getExpirationTemplate'])->name('get_expiration_template');
    Route::get('get-data-message/{id}', [HomeController::class, 'getCustomerData'])->name('get_data_message');
    Route::resource('subscriptions',SubscriptionController::class);
    Route::put('extend-subscription',[SubscriptionController::class,'extend_subscriptions'])->name('extend_subscriptions');
    Route::post('add-profiles',[ProfileController::class, 'add_profiles'])->name('add_profiles');
    Route::put('edit-profile',[ProfileController::class, 'edit_profile'])->name('edit_profile');
    Route::get('get-accounts/{service_id}',[SubscriptionController::class,'getAccounts']);
    Route::get('get-profiles/{account_id}',[SubscriptionController::class,'getProfiles']);
    Route::get('my-accounts',[HomeController::class, 'my_accounts'])->name('my_accounts');
    Route::get('store',[HomeController::class, 'store'])->name('store');
    Route::post('buy-account',[HomeController::class, 'buy_account'])->name('buy_account');
    Route::post('extend-reseller-subscription', [HomeController::class, 'extend_reseller_subscription'])->name("extend_reseller_subscription");
    Route::get('/backup-database', [HomeController::class, 'downloadBackup'])->name('downloadBackup');

    Route::post('report-account',[ReportController::class, 'add_report'])->name('add_report');
    Route::put('edit-report', [ReportController::class, 'edit_report'])->name('edit_report');
});