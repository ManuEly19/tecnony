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
            $table->text('observation')->nullable();

            // RELACION
            // Relación de uno a uno
            $table->unsignedBigInteger('affiliation_tec_id')->unique();
            // Una solicitud de afiliación del lado del técnico tiene una solicitud de afiliación del lado del admin y un solicitud de afiliación del lado del admin le pertenece a una solicitud de afiliación del lado del técnico.
            $table->foreign('affiliation_tec_id')
                ->references('id')
                ->on('affiliation_tecs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Relación de uno a mucho
            $table->unsignedBigInteger('user_ad_id');
            // Un usuario admin tiene que gestionar muchas solicitudes de afiliación y una solicitud de afiliación es gestionada por un admin
            $table->foreign('user_ad_id')
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
        Schema::dropIfExists('affiliation_ads');
    }
};
