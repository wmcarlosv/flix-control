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
                    'text' => 'Profile',
                    'icon' => 'fas fa-user',
                    'route' => 'profile'
                ],
                [
                    'text'=>'Users',
                    'icon'=>'fas fa-users',
                    'route'=>'users.index'
                ],
                [
                    'text'=>'Services',
                    'icon'=>'fas fa-tv',
                    'route'=>'services.index'
                ],
            );
        
        });
    }
}