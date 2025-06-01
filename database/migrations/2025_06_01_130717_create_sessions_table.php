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
        Schema::create('trading_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id');

            $table->string('name')->nullable();

            $table->dateTime('start');
            $table->dateTime('end');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_sessions');
    }
};
