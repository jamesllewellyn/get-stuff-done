<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('priority_id')->nullable();
            $table->unsignedInteger('section_id');
            $table->string('name');
            $table->text('note')->nullable();
            $table->integer('sort_order');
            $table->unsignedInteger('status_id')->nullable();
            $table->date('due_date');
            $table->time('due_time')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('set null');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('set null');
            $table->foreign('created_by_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tasks');
        Schema::enableForeignKeyConstraints();
    }
}
