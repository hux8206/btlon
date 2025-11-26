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
        if (!Schema::hasTable('test')) {
            Schema::create('test', function (Blueprint $table) {
                $table->increments('testID');

                $table->integer('userID')->unsigned();
                $table->foreign('userID')->references('userID')->on('users');

                $table->string('title', 100);
                $table->integer('timeEachQuestion');
                $table->integer('quantity');
                $table->tinyInteger('mode');

                $table->dateTime('dayCreated')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test');
    }
};
