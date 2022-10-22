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
            $table->string('comment', 500)->nullable();
            $table->string('suggestion', 500)->nullable();
            $table->float('qualification', 1, 2)->default(1.0);

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
