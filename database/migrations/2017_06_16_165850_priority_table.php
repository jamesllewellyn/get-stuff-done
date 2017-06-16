<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PriorityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priorities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        $this->addPriorities();
    }
    /**
     * add Priorities to table.
     *
     * @return void
     */
    public function addPriorities(){
        $priorities = ['high', 'medium', 'low'];

        foreach ($priorities as $key => $priority) {
            $new = new \App\Priority();
            $new->name = $priority;
            $new->save();
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('priorities');
    }
}
