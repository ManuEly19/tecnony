<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtenemos todos los servicios
        $services = Service::all();

        // Creamos una imagen por cada servicio
        $services->each(function ($service)
        {
            $service->image()->create(['path' => "https://picsum.photos/id/$service->id/200/300"]);
        });
    }
}
