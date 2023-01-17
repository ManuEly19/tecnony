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
            $table->string('categories', 50)->nullable();
            $table->text('description');
            $table->float('price', 8, 2);
            $table->boolean('state')->default(true);

            // Datos para la atencion
            $table->integer('attention_mode')->default(1); // 1. Fisicamente 2. Domicilio
            $table->text('attention_description')->nullable();

            // Datos para la pago
            $table->integer('payment_method')->default(1); // 1. Fisicamente 2. Domicilio
            $table->text('payment_description')->nullable();

            // REALACION
            // Relación de uno a mucho
            // Relacion con un usuario tecnico
            $table->unsignedBigInteger('user_id');
            // Un usuario técnico proporciona muchos servicio y un servicio le pertenece a un usuario técnico
            $table->foreign('user_id')
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
