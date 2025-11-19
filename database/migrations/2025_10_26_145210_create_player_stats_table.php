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
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id('player_stat_id');
            $table->foreignId('player_id')->nullable()->constrained('players', 'player_id')->nullOnDelete();
            $table->string('name')->unique()->index();
            $table->unsignedInteger('games_played')->default(0);
            $table->unsignedInteger('games_won')->default(0);
            $table->unsignedInteger('games_lost')->default(0);
            $table->unsignedInteger('games_drawn')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
