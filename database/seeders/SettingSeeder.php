<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'title'=>'Flix Control',
            'about'=>'Panel para gestionar cuentas Streaming',
            'expiration_template'=>'
Servicio: #servicio
Cliente:  #cliente
Cuenta: #cuenta
Fecha Facturacion #facturacion
Dias Restantes: #dias
perfil: #perfil
pin: #pin
Clave Cuenta: #clave_cuenta',
            'customer_data_template'=>'
Servicio: #servicio
Cliente:  #cliente
Cuenta: #cuenta
Fecha Facturacion #facturacion
Dias Restantes: #dias
perfil: #perfil
pin: #pin
Clave Cuenta: #clave_cuenta',
            'expiration_days_subscriptions'=>5,
            'expiration_days_accounts'=>10
        ]);
    }
}
