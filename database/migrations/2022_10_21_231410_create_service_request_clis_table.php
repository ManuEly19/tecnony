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
        Schema::create('service_request_clis', function (Blueprint $table) {
            // ID para la tabla de solicitud de servicio del lado del cliente
            $table->id();

            // columnas generales para la tabla
            $table->int('state', 1);
            $table->date('date_issue');

            // columnas de datos del dispositivo para la tabla
            $table->string('device', 50);
            $table->string('model', 50);
            $table->string('brand', 50);
            $table->string('serie', 100)->nullable();
            $table->string('description_problem', 1000);

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
        Schema::dropIfExists('service_request_clis');
    }
};
