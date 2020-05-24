<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemplacantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remplacants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idEquipe');
            $table->integer('idR1');
            $table->integer('idR2');
            $table->integer('idR3');
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
        Schema::dropIfExists('remplacants');
    }
}
