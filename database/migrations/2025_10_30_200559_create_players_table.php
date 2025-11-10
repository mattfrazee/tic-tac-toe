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
        Schema::create('players', function (Blueprint $table) {
            $table->id('player_id');
            $table->string('name')->unique();
            $table->string('email')->nullable()->unique();
            $table->boolean('is_computer')->default(false);
            $table->string('difficulty')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_computer', 'difficulty']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
