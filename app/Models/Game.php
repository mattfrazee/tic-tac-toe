<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'player_x_name',
        'player_o_name',
        'first_player',
        'winner',
        'board_size',
    ];

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
