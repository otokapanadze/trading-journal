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
            $table->foreignId('account_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('symbol_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('direction')->nullable();
            $table->float('pnl')->nullable();
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
