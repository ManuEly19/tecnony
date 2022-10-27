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
        Schema::create('services', function (Blueprint $table) {
            // ID para la tabla de servicios
            $table->id();

            // columna para la tabla
            $table->string('name', 100);
            $table->text('description');
            $table->float('price', 8, 2);
            $table->boolean('state')->default(true);

            // REALACION
            // Relación de uno a mucho
            $table->unsignedBigInteger('user_tec_id');
            // Un usuario técnico proporciona muchos servicio y un servicio le pertenece a un usuario técnico
            $table->foreign('user_tec_id')
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
        Schema::dropIfExists('services');
    }
};
