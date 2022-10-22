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
            $table->string('attention_schedule', 100)->nullable();
            $table->string('local_name', 50)->nullable();
            $table->string('local_address', 100)->nullable();
            $table->boolean('confirmation')->default(false);

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
        Schema::dropIfExists('affiliation_tecs');
    }
};
