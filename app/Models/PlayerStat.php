<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $player_stat_id
 * @property int|null $player_id
 * @property string $name
 * @property int $games_played
 * @property int $games_won
 * @property int $games_lost
 * @property int $games_drawn
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read float $loss_percentage
 * @property-read float $win_percentage
 * @property-read Player|null $player
 * @method static Builder<static>|PlayerStat newModelQuery()
 * @method static Builder<static>|PlayerStat newQuery()
 * @method static Builder<static>|PlayerStat query()
 * @method static Builder<static>|PlayerStat top(int $limit = 10)
 * @method static Builder<static>|PlayerStat whereCreatedAt($value)
 * @method static Builder<static>|PlayerStat whereDeletedAt($value)
 * @method static Builder<static>|PlayerStat whereGamesDrawn($value)
 * @method static Builder<static>|PlayerStat whereGamesLost($value)
 * @method static Builder<static>|PlayerStat whereGamesPlayed($value)
 * @method static Builder<static>|PlayerStat whereGamesWon($value)
 * @method static Builder<static>|PlayerStat whereName($value)
 * @method static Builder<static>|PlayerStat wherePlayerId($value)
 * @method static Builder<static>|PlayerStat wherePlayerStatId($value)
 * @method static Builder<static>|PlayerStat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlayerStat extends Model
{
    protected $table = 'player_stats';
    protected $primaryKey = 'player_stat_id';

    protected $fillable = [
        'player_id',
        'name',
        'games_played',
        'games_won',
        'games_lost',
        'games_drawn',
    ];

    protected $casts = [
        'player_id' => 'int',
        'games_played' => 'int',
        'games_won' => 'int',
        'games_lost' => 'int',
        'games_drawn' => 'int',
    ];

    protected $appends = [
        'win_percentage',
        'loss_percentage',
    ];

    /**
     * Scope: Top players ranked by win ratio, then total wins.
     */
    public function scopeTop(Builder $query, int $limit = 10): Builder
    {
        return $query
            ->orderByDesc('games_won')
            ->orderByRaw("
                CASE
                    WHEN (games_won + games_lost) > 0
                    THEN (games_won * 1.0 / (games_won + games_lost))
                    ELSE 0
                END DESC
            ")
            ->orderByDesc('games_played')
            ->limit($limit);
    }

    /**
     * Helpers: Win/loss rates (excluding draws from denominator).
     */
    public function winRate(): float
    {
        $decidedGames = $this->games_won + $this->games_lost;
        return $decidedGames > 0
            ? round(($this->games_won / $decidedGames) * 100, 1)
            : 0.0;
    }

    public function lossRate(): float
    {
        $decidedGames = $this->games_won + $this->games_lost;
        return $decidedGames > 0
            ? round(($this->games_lost / $decidedGames) * 100, 1)
            : 0.0;
    }

    public function getWinPercentageAttribute(): float
    {
        return $this->winRate();
    }

    public function getLossPercentageAttribute(): float
    {
        return $this->lossRate();
    }

    /**
     * Relationships
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }
}
