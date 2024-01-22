<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Auth;

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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            $users = [];
            $settings = [];

            $role = Auth::user()->role;

            if($role == "super_admin"){
                $users = [
                    'text'=>'Usuarios',
                    'icon'=>'fas fa-users',
                    'route'=>'users.index'
                ];

                $settings = [
                    'text'=>'Configuracion',
                    'icon'=>'fas fa-list',
                    'route'=>'settings.index'
                ];
            }

            if(env('LOCAL_MANAGER') == 'yes'){
                $settings = [
                    'text'=>'Configuracion',
                    'icon'=>'fas fa-list',
                    'route'=>'settings.index'
                ];
            }

            $event->menu->add(
                [
                    'text'=>'Dashboard',
                    'icon'=>'fas fa-cogs',
                    'route'=>'dashboard'
                ],
                $settings,
                [
                    'text' => 'Perfil',
                    'icon' => 'fas fa-user',
                    'route' => 'profile'
                ],
                $users,
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
                    'text'=>'Movimientos',
                    'icon'=>'fas fa-calculator',
                    'route'=>'movements.index'
                ],
            );
        
        });
    }
}