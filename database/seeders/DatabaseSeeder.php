<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AffiliationAd;
use App\Models\AffiliationTec;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // No Corre mas el migrate
            RoleSeeder::class,
            UserSeeder::class,
            ServiceSeeder::class,
            ServiceRequestCliSeeder::class,
            ServiceRequestTecSeeder::class,
            SatisfactionFormSeeder::class,
            AffiliationAdSeeder::class,
            AffiliationTecSeeder::class,
            ImageSeeder::class
        ]);
    }
}
