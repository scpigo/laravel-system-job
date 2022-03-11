<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('action', 100);
            $table->string('action_vars', 1000);
            $table->timestamp('scheduled_at');
            $table->timestamp('created_at');
            $table->timestamp('executed_at')->nullable();
            $table->integer('attempt')->nullable();
            $table->string('event_id', 100)->nullable();
            $table->string('status', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_jobs');
    }
}
