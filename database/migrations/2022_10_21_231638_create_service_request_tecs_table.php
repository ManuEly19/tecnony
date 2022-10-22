<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_request_tecs', function (Blueprint $table) {
            // ID para la tabla de solicitud de servicio del lado del cliente
            $table->id();

            // columnas generales para la tabla
            $table->int('state', 1);
            $table->date('start_date');
            $table->string('diagnosis', 1000);

            // columnas de datos de finalizacion del servicios para la tabla
            $table->string('incident_resolution', 1000);
            $table->string('warranty', 100)->nullable();
            $table->string('spare_parts', 1000)->nullable();
            $table->float('price_spare_parts', 8, 2)->nullable();
            $table->float('final_price', 8, 2);
            $table->date('end_date');

            // columnas de datos de control para la tabla
            $table->string('observation', 1000)->nullable();

            // RELACION

            // columnas especiales para la tabla
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_request_tecs');
    }
};
