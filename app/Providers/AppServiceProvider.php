<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {

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
                    'text'=>'Usuarios',
                    'icon'=>'fas fa-users',
                    'route'=>'users.index'
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
            );
        
        });
    }
}