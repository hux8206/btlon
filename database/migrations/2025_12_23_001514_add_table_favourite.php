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
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->integer('userID')->unsigned();
            $table->integer('testID')->unsigned();
            $table->timestamp('created_at')->useCurrent();

            // Khóa ngoại (nếu muốn chặt chẽ)
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('testID')->references('testID')->on('test')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
