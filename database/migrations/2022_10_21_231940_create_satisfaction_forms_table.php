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
        Schema::create('satisfaction_forms', function (Blueprint $table) {
            // ID para la tabla de formulario de satisfacion
            $table->id();

            // columna para la tabla
            $table->string('comment', 1000)->nullable();
            $table->string('suggestion', 1000)->nullable();
            $table->float('qualification', 1, 2)->default(1.0);

            // RELACION
            // De uno a uno
            $table->unsignedBigInteger('service_request_id')->unique();
            //Una solicitud de servicio tiene un formulario de satisfacción y un formulario de satisfaction le pertenece a una solicitud de servicio.
            $table->foreign('service_request_id')
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
        Schema::dropIfExists('satisfaction_forms');
    }
};
