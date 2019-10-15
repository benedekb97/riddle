<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('help')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('riddle_id')->unsigned();
            $table->foreign('riddle_id')->references('id')->on('riddles')->onDelete('cascade');
            $table->bigInteger('helped_by')->unsigned()->nullable();
            $table->foreign('helped_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('helped_at')->nullable();
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
        Schema::dropIfExists('helps');
    }
}
