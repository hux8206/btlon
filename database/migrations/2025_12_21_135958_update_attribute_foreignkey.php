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
        Schema::table('vocabulary', function (Blueprint $table) {
            $table->dropForeign(['testID']);
            $table->foreign('testID')
                ->references('testID')
                ->on('test')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vocabulary', function (Blueprint $table) {
            $table->dropForeign(['testID']);
            $table->foreign('testID')
                ->references('testID')
                ->on('test');
        });
    }
};
