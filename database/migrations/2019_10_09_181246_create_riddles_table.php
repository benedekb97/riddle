<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiddlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riddles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('image');
            $table->string('answer');
            $table->integer('difficulty')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('approved')->default(false);
            $table->bigInteger('approved_by')->unsigned()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->boolean('blocked')->default(false);
            $table->bigInteger('blocked_by')->unsigned()->nullable();
            $table->foreign('blocked_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('blocked_at')->nullable();
            $table->string('block_reason')->nullable();
            $table->integer('number')->nullable();
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
        Schema::dropIfExists('riddles');
    }
}
