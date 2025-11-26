<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('historyID');
            $table->integer('testID')->unsigned();
            $table->integer('userID')->unsigned();
            $table->integer('correct_question');
            $table->integer('question_completed');
            $table->foreign('userID')->references('userID')->on('users');
            $table->foreign('testID')->references('testID')->on('test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
