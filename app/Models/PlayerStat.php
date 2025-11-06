<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $player_stat_id
 * @property string $name
 * @property int $games_played
 * @property int $games_won
 * @property int $games_lost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $games_drawn
 * @property-read float $loss_percentage
 * @property-read float $win_percentage
 * @method static Builder<static>|PlayerStat newModelQuery()
 * @method static Builder<static>|PlayerStat newQuery()
 * @method static Builder<static>|PlayerStat query()
 * @method static Builder<static>|PlayerStat top(int $limit = 10)
 * @method static Builder<static>|PlayerStat whereCreatedAt($value)
 * @method static Builder<static>|PlayerStat whereDeletedAt($value)
 * @method static Builder<static>|PlayerStat whereGamesLost($value)
 * @method static Builder<static>|PlayerStat whereGamesPlayed($value)
 * @method static Builder<static>|PlayerStat whereGamesWon($value)
 * @method static Builder<static>|PlayerStat whereName($value)
 * @method static Builder<static>|PlayerStat wherePlayerStatId($value)
 * @method static Builder<static>|PlayerStat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlayerStat extends Model
{
    protected $table = 'player_stats';
    protected $primaryKey = 'player_stat_id';

    protected $fillable = [
        'name',
        'games_played',
        'games_won',
        'games_lost',
    ];

    protected $casts = [
        'games_played' => 'int',
        'games_won' => 'int',
        'games_lost' => 'int',
    ];

    protected $appends = [
        'win_percentage',
        'loss_percentage',
        'games_drawn',
    ];

    /**
     * Scope: Highest ranking players
     */
    public function scopeTop(Builder $query, int $limit = 10): Builder
    {
        return $query
            ->orderBy('games_won', 'desc')
            ->orderByRaw(
                "CASE
                    WHEN games_played > 0
                    THEN (games_won * 1.0 / games_played)
                    ELSE 0
                END DESC"
            )
            ->orderBy('games_played', 'desc')
            ->limit($limit);
    }

    /**
     * Helpers: win/loss rate percentages
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

    protected function gamesDrawn(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->games_played - ($this->games_won + $this->games_lost)
        );
    }
}
