<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('saison');
            $table->integer('idClub1');
            $table->integer('idClub2');
            $table->string('stade');
            $table->integer('scoreClub1');
            $table->integer('scoreClub2');
            $table->integer('nbSpectateurs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
