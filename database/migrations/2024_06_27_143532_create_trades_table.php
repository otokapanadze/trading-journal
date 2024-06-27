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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

            $table->string('symbol')->nullable();
            $table->string('direction')->nullable();
            $table->float('pnl')->nullable();
//            $table->unsignedBigInteger('user_id');
//            $table->foreign('user_id')->references('id')->on('users');
//            $table->unsignedBigInteger('account_id');
//            $table->foreign('account_id')->references('id')->on('accounts');
            $table->dateTime('open_at')->nullable();
            $table->dateTime('closes_at')->nullable();
            $table->json('images')->nullable();
            $table->json('params')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
