<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicalARSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical__a_r_s', function (Blueprint $table) {
            $table->id();
            $table->string('caseName');
            $table->text('description');
            $table->text('solution');
            $table->string('caseImg');
            $table->string('caseVideo');
            $table->string('category')->default('medical');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('medical_id');
            $table->foreign('medical_id')->references('id')->on('medicals')->onDelete('cascade');
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
        Schema::dropIfExists('medical__a_r_s');
    }
}
