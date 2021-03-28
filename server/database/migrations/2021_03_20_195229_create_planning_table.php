<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Planning;

class CreatePlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_action')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->unsignedBigInteger('start_timestamp');
            $table->unsignedBigInteger('end_timestamp');
            $table->integer('state')->default(Planning::$STATES["not_allocated"]);
            $table->unsignedBigInteger('id_user_state')->nullable();
            $table->longText('reason_state')->nullable();
            $table->datetime('date_state')->nullable();
            $table->unsignedBigInteger('timestamp_state')->nullable();
            $table->text('info')->nullable();
            $table->integer('late')->default(0);
            $table->string('reference')->unique();
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_user_state')->references('id')->on('users');
            $table->foreign('id_action')->references('id')->on('actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planning');
    }
}
