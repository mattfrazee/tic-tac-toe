<?php

namespace App\Models;

use App\Enums\DifficultyLevel;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $player_id
 * @property string $name
 * @property string|null $email
 * @property bool $is_computer
 * @property DifficultyLevel|null $difficulty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<int, Game> $games
 * @property-read int|null $games_count
 * @property-read Collection<int, PlayerStat> $stats
 * @property-read int|null $stats_count
 * @method static Builder<static>|Player newModelQuery()
 * @method static Builder<static>|Player newQuery()
 * @method static Builder<static>|Player query()
 * @method static Builder<static>|Player whereCreatedAt($value)
 * @method static Builder<static>|Player whereDeletedAt($value)
 * @method static Builder<static>|Player whereDifficulty($value)
 * @method static Builder<static>|Player whereEmail($value)
 * @method static Builder<static>|Player whereIsComputer($value)
 * @method static Builder<static>|Player whereName($value)
 * @method static Builder<static>|Player wherePlayerId($value)
 * @method static Builder<static>|Player whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Player extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'player_id';

    protected $fillable = [
        'name',
        'email',
        'is_computer',
        'difficulty',
    ];

    protected $casts = [
        'is_computer' => 'boolean',
        'difficulty' => DifficultyLevel::class,
    ];

    /**
     * Relationships
     */
    public function stats(): HasMany
    {
        return $this->hasMany(PlayerStat::class, 'player_id', 'player_id');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'player_x_name', 'name')
            ->orWhere('player_o_name', $this->name);
    }
}
