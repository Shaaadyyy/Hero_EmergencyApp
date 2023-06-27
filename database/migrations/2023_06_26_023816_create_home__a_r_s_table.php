<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeARSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home__a_r_s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('caseName');
            $table->text('description');
            $table->text('solution');
            $table->string('caseImg');
            $table->string('caseVideo');
            $table->string('category')->default('home');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('home_id')->unsigned();
            $table->foreign('home_id')->references('id')->on('homes')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home__a_r_s');
    }
}
