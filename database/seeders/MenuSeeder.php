<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'nombre' => 'Dashboard',
                'url' => '/dashboard',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 1, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Usuarios',
                'url' => '/usuarios',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'Reportes',
                'url' => '/reportes',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 2, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Configuración',
                'url' => '/configuracion',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 1, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Clientes',
                'url' => '/clientes',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 3, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Ventas',
                'url' => '/ventas',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 3, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Soporte',
                'url' => '/soporte',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 4, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Inventario',
                'url' => '/inventario',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 4, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Contabilidad',
                'url' => '/contabilidad',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 2, 
                'estado' => 1,
            ],
            [
                'nombre' => 'Configuración Avanzada',
                'url' => '/configuracion-avanzada',
                'icono' => 'pi pi-fw pi-home',
                'rol' => 1, 
                'estado' => 1,
            ],
        ]);
    }
}
