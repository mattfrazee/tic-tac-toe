<?php

use App\Enums\DifficultyLevel;
use App\Enums\PlayerMark;
use App\Enums\WinnerResult;
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
        Schema::create('games', function (Blueprint $table) {
            $table->id('game_id');
            $table->foreignId('room_code_id')->nullable()->constrained('room_codes','room_code_id');
            $table->foreignId('player_x_id')->nullable()->constrained('players','player_id');
            $table->foreignId('player_o_id')->nullable()->constrained('players','player_id');
            $table->string('player_x_name')->nullable();
            $table->string('player_o_name')->nullable();
            $table->enum('first_player', PlayerMark::values())->nullable();
            $table->enum('winner', WinnerResult::values())->nullable();
            $table->unsignedTinyInteger('board_size')->default(3);
            $table->boolean('vs_computer')->default(false);
            $table->enum('difficulty', DifficultyLevel::values())->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
