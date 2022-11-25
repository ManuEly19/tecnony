<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtenemos los usarios tecnicos
        $users_tecnicos = User::where('role_id', 2)->get();
        // dd($users_tecnico);
        // dd(count($users_guards));

        // Para cada tecnico se asigna 2 servicios
        $users_tecnicos->each(function($users_tecnico) {
            Service::factory()->for($users_tecnico)->count(2)->create();
        });

    }
}
