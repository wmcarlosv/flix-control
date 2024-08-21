<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Auth;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Schema::defaultStringLength(191);
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $data = Setting::first();
            $help_url = [];
            if($data){
                if(!empty($data->help_url)){
                    $help_url = [
                        'text'=>'Ayuda',
                        'icon'=>'fas fa-info-circle',
                        'url'=>$data->help_url,
                        'target'=>'_blank'
                    ];
                }
            }else{
                $data = [];
            }

            $role = Auth::user()->role;
            if($role == "super_admin" || $role == "admin"){
                $event->menu->add(
                    [
                        'text'=>'Dashboard',
                        'icon'=>'fas fa-cogs',
                        'route'=>'dashboard'
                    ],
                    [
                        'text'=>'Configuracion',
                        'icon'=>'fas fa-list',
                        'route'=>'settings.index'
                    ],
                    [
                        'text' => 'Perfil',
                        'icon' => 'fas fa-user',
                        'route' => 'profile'
                    ],
                    [
                        'text'=>'Usuarios',
                        'icon'=>'fas fa-users',
                        'route'=>'users.index'
                    ],
                    [
                        'text'=>'Creditos',
                        'icon'=>'fas fa-dollar-sign',
                        'route'=>'credits.index'
                    ],
                    [
                        'text'=>'Servicios',
                        'icon'=>'fas fa-tv',
                        'route'=>'services.index'
                    ],
                    [
                        'text'=>'Clientes',
                        'icon'=>'fas fa-walking',
                        'route'=>'customers.index'
                    ],
                    [
                        'text'=>'Cuentas',
                        'icon'=>'fas fa-piggy-bank',
                        'route'=>'accounts.index'
                    ],
                    [
                        'text'=>'Suscripciones',
                        'icon'=>'fas fa-star',
                        'route'=>'subscriptions.index'
                    ],
                    [
                        'text'=>'Movimientos',
                        'icon'=>'fas fa-calculator',
                        'route'=>'movements.index'
                    ],
                    $help_url
                );
            }else{
                $event->menu->add(
                    [
                        'text'=>'Dashboard',
                        'icon'=>'fas fa-cogs',
                        'route'=>'dashboard'
                    ],
                    [
                        'text' => 'Perfil',
                        'icon' => 'fas fa-user',
                        'route' => 'profile'
                    ],
                    [
                        'text'=>'Clientes',
                        'icon'=>'fas fa-walking',
                        'route'=>'customers.index'
                    ],
                    [
                        'text'=>'Mis Cuentas',
                        'icon'=>'fas fa-hand-sparkles',
                        'route'=>'my_accounts'
                    ],
                    [
                        'text'=>'Suscripciones',
                        'icon'=>'fas fa-star',
                        'route'=>'subscriptions.index'
                    ],
                    [
                        'text'=>'Movimientos',
                        'icon'=>'fas fa-calculator',
                        'route'=>'movements.index'
                    ],
                    [
                        'text'=>'Tienda',
                        'icon'=>'fas fa-store',
                        'route'=>'store'
                    ],
                    $help_url
                );
            }
        });
    }
}