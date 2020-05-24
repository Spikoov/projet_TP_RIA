<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJoueursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joueurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prenom');
            $table->string('nom');
            $table->integer('age');
            $table->string('poste');
            $table->integer('tir');
            $table->integer('passe');
            $table->integer('technique');
            $table->integer('placement');
            $table->integer('vitesse');
            $table->integer('tacle');
            $table->integer('arret');
            $table->integer('forme');
            $table->integer('endurance');
            $table->integer('noteGlobale');
            $table->integer('noteInstantanee');
            $table->boolean('sousContrat');
            $table->integer('dureeContrat');
            $table->integer('salaire');
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
        Schema::dropIfExists('joueurs');
    }
}
