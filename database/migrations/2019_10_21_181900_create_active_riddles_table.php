<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveRiddlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_riddles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('riddle_id')->unsigned();
            $table->foreign('riddle_id')->references('id')->on('riddles')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_current_riddle_foreign');
            $table->dropColumn('current_riddle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('current_riddle')->unsigned()->nullable();
            $table->foreign('current_riddle')->references('id')->on('riddles')->onDelete('set null');
        });

        Schema::dropIfExists('active_riddles');
    }
}
