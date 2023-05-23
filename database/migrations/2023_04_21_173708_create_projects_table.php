<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', [
                \App\Constants\ProjectCategory::work,
                \App\Constants\ProjectCategory::personal,
                \App\Constants\ProjectCategory::wishlist,
                \App\Constants\ProjectCategory::birthdays,
            ])->default(\App\Constants\ProjectCategory::work);
//            $table->enum('category', ['work', 'personal'  , 'wishlist' , 'birthdays']);
//            $table->unsignedTinyInteger('priority')->default(\App\Constants\TaskPriority::important_AND_URGENT);
            $table->enum('priority', [
                \App\Constants\TaskPriority::important_AND_URGENT,
                \App\Constants\TaskPriority::important_But_Not_URGENT,
                \App\Constants\TaskPriority::not_Important_Or_URGENT,
                \App\Constants\TaskPriority::not_A_Proirity,
            ])->default(\App\Constants\TaskPriority::not_A_Proirity);
            $table->enum('status', ['active', 'done']);
            $table->time('time_Hours');
            $table->time('time_Min');
            $table->enum('time_Am_BM', ['am', 'pm']);
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
        Schema::dropIfExists('projects');
    }
}


