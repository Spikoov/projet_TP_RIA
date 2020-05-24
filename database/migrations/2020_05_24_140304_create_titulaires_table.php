<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTitulairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titulaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idEquipe');
            $table->integer('idT1');
            $table->integer('idT2');
            $table->integer('idT3');
            $table->integer('idT4');
            $table->integer('idT5');
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
        Schema::dropIfExists('titulaires');
    }
}
