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
            // ID para la tabla de solicitud de servicio del lado del tecnico
            $table->id();

            // columnas generales para la tabla
            $table->int('state', 1);
            $table->date('start_date');
            $table->text('diagnosis');

            // columnas de datos de finalizacion del servicios para la tabla
            $table->text('incident_resolution');
            $table->string('warranty', 500)->nullable();
            $table->text('spare_parts')->nullable();
            $table->float('price_spare_parts', 8, 2)->nullable();
            $table->float('final_price', 8, 2);
            $table->date('end_date');

            // columnas de datos de control para la tabla
            $table->text('observation')->nullable();

            // RELACION
            // Relación de uno a uno
            $table->unsignedBigInteger('service_request_cli_id')->unique();
            // Solicitud de servicio de lado del cliente tiene una solicitud de servicios del lado del técnico y una solicitud de servicios del lado del técnico le pertenece a una solicitud de servicio del lado del cliente.
            $table->foreign('service_request_cli_id')
                ->references('id')
                ->on('service_request_clis')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
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
