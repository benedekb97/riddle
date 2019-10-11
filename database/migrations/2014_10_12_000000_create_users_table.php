<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('surname');
            $table->string('given_names');
            $table->string('email')->unique();
            $table->string('internal_id')->unique()->nullable();
            $table->string('password');
            $table->bigInteger('points')->nullable()->default(0);
            $table->boolean('moderator')->default(false);
            $table->boolean('approved')->default(false)->nullable();
            $table->bigInteger('current_riddle')->unsigned()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
