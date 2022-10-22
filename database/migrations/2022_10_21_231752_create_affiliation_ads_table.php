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
        Schema::create('affiliation_ads', function (Blueprint $table) {
            // ID para la tabla de afiliacion del lado del admin
            $table->id();

            // columnas de datos de gestion del admin para la tabla
            $table->int('state', 1);
            $table->date('date_acceptance');

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
        Schema::dropIfExists('affiliation_ads');
    }
};
