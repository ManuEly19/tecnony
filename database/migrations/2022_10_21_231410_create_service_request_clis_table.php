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
            $table->integer('state');
            $table->date('date_issue');

            // columnas de datos del dispositivo para la tabla
            $table->string('device', 50);
            $table->string('model', 50);
            $table->string('brand', 50);
            $table->string('serie', 100)->nullable();
            $table->text('description_problem');

            // RELACIONES
            // Relación de uno a mucho
            $table->unsignedBigInteger('service_id')->nullable();
            // Un servicio tiene de uno a muchos solicitudes de servicio y una solicitud de servicios le pertenece a un servicio.
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('set null')
                ->onUpdate('cascade');

            // Relación de uno a mucho
            $table->unsignedBigInteger('user_cli_id');
            // Un usuario cliente hace de uno a muchas solicitudes de servicio y una solicitud de servicio le pertenece a un usuario cliente
            $table->foreign('user_cli_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('service_request_clis');
    }
};
