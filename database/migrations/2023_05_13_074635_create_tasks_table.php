<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category')->on('projects');
            $table->enum('status', ['active', 'done']);
            $table->string('description');
            $table->time('time_Hours')->default('00:00:00');
            $table->time('time_Min');
            $table->enum('time_Am_BM', ['am', 'pm']);
            $table->enum('sort_by', ['importance', 'alphabetical-order' , 'due-date' , 'created-at']);
            $table->foreignId('project_id');   // to extend the category from the products table //
            $table->foreign('project_id')->references('id')->on('projects');
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
        Schema::dropIfExists('tasks');
    }
}
