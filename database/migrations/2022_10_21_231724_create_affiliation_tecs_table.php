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
        Schema::create('affiliation_tecs', function (Blueprint $table) {
            // ID para la tabla de afiliacion del lado del tecnico
            $table->id();

            // columnas generales para la tabla
            $table->int('state', 1);
            $table->date('date_issue');

            // columnas de datos laborales del tecnico para la tabla
            $table->string('profession', 50);
            $table->string('specialization', 50);
            $table->integer('work_phone', 10);
            $table->text('attention_schedule')->nullable();
            $table->string('local_name', 50)->nullable();
            $table->string('local_address', 300)->nullable();
            $table->boolean('confirmation')->default(false);

            // RELACION
            // De uno a uno
            $table->unsignedBigInteger('user_tec_id')->unique();
            // Una solicitud de afiliación es hecha por un usuario técnico y un usuario técnico le pertenece una solicitud.
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
        Schema::dropIfExists('affiliation_tecs');
    }
};
