<?php

namespace Database\Seeders;

use App\Models\AffiliationTec;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AffiliationTecSeeder extends Seeder
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

        //Creamos una solicitud de afiliacion a cada tecnico
        $users_tecnicos->each(function ($users_tecnico) {
            AffiliationTec::factory()->for($users_tecnico)->count(1)->create();
        });
    }
}
