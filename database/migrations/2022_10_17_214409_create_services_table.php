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
            // ID para la tabla de la BDD
            $table->id();

            // columna para la tabla BDD
            $table->string('name', 100);
            $table->string('description', 500);
            $table->float('price', 8, 2);
            $table->boolean('state')->default(true);

            // Relación de uno a mucho
            $table->unsignedBigInteger('user_id');

            // Un usuario técnico proporciona muchos servicio y un servicio le pertenece a un usuario técnico
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // columnas especiales para la tabla de la BDD
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
