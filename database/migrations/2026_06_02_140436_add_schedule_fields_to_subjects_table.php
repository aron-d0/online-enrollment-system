<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {

            $table->string('days')->nullable();

            $table->string('time_from')->nullable();

            $table->string('time_to')->nullable();

            $table->string('room')->nullable();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {

            $table->dropColumn([
                'days',
                'time_from',
                'time_to',
                'room'
            ]);

        });
    }
};
