<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalkThrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('walk_throws', function (Blueprint $table) {
            $table->id();
            $table->string('f_img');
            $table->string('f_title');
            $table->string('f_description');
            $table->string('s_img');
            $table->string('s_title');
            $table->string('s_description');
            $table->string('t_img');
            $table->string('t_title');
            $table->string('t_description');
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('walk_throws');
    }
}
