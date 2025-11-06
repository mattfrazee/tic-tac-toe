<?php

use App\Enums\PlayerMark;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->id('move_id');
            $table->foreignId('game_id')->constrained('games','game_id')->cascadeOnDelete();
            $table->enum('mark', PlayerMark::values());
            $table->unsignedTinyInteger('row');
            $table->unsignedTinyInteger('col');
            $table->unsignedSmallInteger('turn');
            $table->boolean('is_computer')->default(false);
            $table->timestamps();
            $table->unique(['game_id', 'row', 'col']);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
