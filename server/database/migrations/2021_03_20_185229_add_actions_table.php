<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Action;

class AddActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->integer('type');
            $table->longText('data')->nullable();
            $table->longText('public_data')->nullable();
            $table->integer('state')->default(Action::$STATES["waiting_validation"]);
            $table->unsignedBigInteger('id_user_state')->nullable();
            $table->longText('reason_state')->nullable();
            $table->datetime('date_state')->nullable();
            $table->unsignedBigInteger('timestamp_state')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_user_state')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actions');
    }
}
