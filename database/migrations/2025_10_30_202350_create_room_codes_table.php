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
        Schema::create('room_codes', function (Blueprint $table) {
            $table->id('room_code_id');
            $table->string('code', 6)->unique();
            $table->unsignedInteger('active_players')->default(0);
            $table->string('player_x_name')->nullable();
            $table->string('player_o_name')->nullable();
            $table->foreignId('player_x_id')->nullable()->constrained('players','player_id');
            $table->foreignId('player_o_id')->nullable()->constrained('players','player_id');
            $table->date('expires_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_codes');
    }
};
