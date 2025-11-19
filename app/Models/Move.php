<?php

namespace App\Models;

use App\Enums\PlayerMark;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $move_id
 * @property int $game_id
 * @property PlayerMark $mark
 * @property int $row
 * @property int $col
 * @property int $turn
 * @property bool $is_computer
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Game $game
 * @method static Builder<static>|Move computer()
 * @method static Builder<static>|Move human()
 * @method static Builder<static>|Move newModelQuery()
 * @method static Builder<static>|Move newQuery()
 * @method static Builder<static>|Move onlyTrashed()
 * @method static Builder<static>|Move query()
 * @method static Builder<static>|Move whereCol($value)
 * @method static Builder<static>|Move whereCreatedAt($value)
 * @method static Builder<static>|Move whereDeletedAt($value)
 * @method static Builder<static>|Move whereGameId($value)
 * @method static Builder<static>|Move whereIsComputer($value)
 * @method static Builder<static>|Move whereMark($value)
 * @method static Builder<static>|Move whereMoveId($value)
 * @method static Builder<static>|Move whereRow($value)
 * @method static Builder<static>|Move whereTurn($value)
 * @method static Builder<static>|Move whereUpdatedAt($value)
 * @method static Builder<static>|Move withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Move withoutTrashed()
 * @mixin Eloquent
 */
class Move extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'move_id';

    protected $fillable = [
        'game_id',
        'mark',
        'row',
        'col',
        'turn',
        'is_computer',
    ];

    protected $casts = [
        'game_id' => 'int',
        'mark' => PlayerMark::class,
        'row' => 'int',
        'col' => 'int',
        'turn' => 'int',
        'is_computer' => 'boolean',
    ];

    public function scopeComputer(Builder $query): Builder
    {
        return $query->where('is_computer', true);
    }

    public function scopeHuman(Builder $query): Builder
    {
        return $query->where('is_computer', false);
    }

    public function isComputerMove(): bool
    {
        return (bool) $this->is_computer;
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}
