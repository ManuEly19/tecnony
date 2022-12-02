<?php

namespace Database\Seeders;

use App\Models\SatisfactionForm;
use App\Models\ServiceRequestCli;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatisfactionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtenemos todo las solicitudes del lado del cliente
        //Creamos 30 solicitudes de servicio del lado del cliente
        SatisfactionForm::factory()->count(30)->create();
    }
}
