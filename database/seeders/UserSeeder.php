<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // https://laravel.com/docs/9.x/database-testing#belongs-to-relationships
        //dd($user_admin);

        //Asignamos 5 usarios para cada rol
        $rol_admin = Role::where('name', 'admin')->first();
        // 5 usuarios que le pertenecen al rol admi
        User::factory()->for($rol_admin)->count(5)->create();

        $rol_tecnico = Role::where('name', 'tecnico')->first();
        // 5 usuarios que le pertenecen al rol director
        User::factory()->for($rol_tecnico)->count(5)->create();

        $rol_cliente = Role::where('name', 'cliente')->first();
        // 5 usuarios que le pertenecen al rol guard
        User::factory()->for($rol_cliente)->count(5)->create();
    }
}
