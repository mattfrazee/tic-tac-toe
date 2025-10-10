<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Move extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'game_id',
        'mark',
        'row',
        'col',
        'turn',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
