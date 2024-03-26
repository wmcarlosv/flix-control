<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
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
                        'text'=>'Subscripciones',
                        'icon'=>'fas fa-star',
                        'route'=>'subscriptions.index'
                    ],
                    [
                        'text'=>'Movimientos',
                        'icon'=>'fas fa-calculator',
                        'route'=>'movements.index'
                    ]
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
                        'text'=>'Subscripciones',
                        'icon'=>'fas fa-star',
                        'route'=>'subscriptions.index'
                    ],
                    [
                        'text'=>'Movimientos',
                        'icon'=>'fas fa-calculator',
                        'route'=>'movements.index'
                    ]
                );
            }
        });
    }
}