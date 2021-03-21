<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Books extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('books', function(Blueprint $schema){
            $schema->bigIncrements('id');
            $schema->string('Title');
            $schema->string('Authtor');
            $schema->string('releasedYear');
            $schema->unsignedBigInteger('userid');
            $schema->foreign('userid')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
