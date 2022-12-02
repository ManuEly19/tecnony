<?php

namespace Database\Seeders;

use App\Models\AffiliationAd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AffiliationAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creamos 5 aceptaciones de afiliacion lado del admin
        AffiliationAd::factory()->count(5)->create();
    }
}
