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
            $table->string('Fimg');
            $table->string('Ftitle');
            $table->string('Fdescription');
            $table->string('Simg');
            $table->string('Stitle');
            $table->string('Sdescription');
            $table->string('Timg');
            $table->string('Ttitle');
            $table->string('Tdescription');
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
