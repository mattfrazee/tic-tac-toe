<?php

namespace App\Models;

use App\Enums\PlayerMark;
use App\Enums\WinnerResult;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $game_id
 * @property string $player_x_name
 * @property string $player_o_name
 * @property int|null $player_x_id
 * @property int|null $player_o_id
 * @property PlayerMark $first_player
 * @property WinnerResult|null $winner
 * @property int $board_size
 * @property bool $vs_computer
 * @property int|null $room_code_id
 * @property int $is_online
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, Move> $moves
 * @property-read int|null $moves_count
 * @property-read RoomCode|null $room_code
 * @method static Builder<static>|Game newModelQuery()
 * @method static Builder<static>|Game newQuery()
 * @method static Builder<static>|Game onlyTrashed()
 * @method static Builder<static>|Game query()
 * @method static Builder<static>|Game whereBoardSize($value)
 * @method static Builder<static>|Game whereCreatedAt($value)
 * @method static Builder<static>|Game whereDeletedAt($value)
 * @method static Builder<static>|Game whereFirstPlayer($value)
 * @method static Builder<static>|Game whereGameId($value)
 * @method static Builder<static>|Game whereIsOnline($value)
 * @method static Builder<static>|Game wherePlayerOId($value)
 * @method static Builder<static>|Game wherePlayerOName($value)
 * @method static Builder<static>|Game wherePlayerXId($value)
 * @method static Builder<static>|Game wherePlayerXName($value)
 * @method static Builder<static>|Game whereRoomCodeId($value)
 * @method static Builder<static>|Game whereUpdatedAt($value)
 * @method static Builder<static>|Game whereVsComputer($value)
 * @method static Builder<static>|Game whereWinner($value)
 * @method static Builder<static>|Game withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Game withoutTrashed()
 * @mixin Eloquent
 */
class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'game_id';

    protected $fillable = [
        'player_x_name',
        'player_o_name',
        'first_player',
        'winner',
        'board_size',
        'vs_computer',
        'is_online',
        'room_code_id',
    ];

    protected $casts = [
        'first_player' => PlayerMark::class,
        'winner' => WinnerResult::class,
        'vs_computer' => 'boolean',
        'is_online' => 'boolean',
    ];

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class, 'game_id', 'game_id');
    }

    public function room_code(): HasOne
    {
        return $this->hasOne(RoomCode::class, 'room_code_id', 'room_code_id');
    }
}
