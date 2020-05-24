<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idClub');
            $table->binary('joueursTitulaires');
            $table->binary('joueursRemplacants');
            $table->binary('joueursAutres')->nullable();
            $table->string('organisation');
            $table->integer('noteAbsolue');
            $table->integer('notePartielle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipes');
    }
}
