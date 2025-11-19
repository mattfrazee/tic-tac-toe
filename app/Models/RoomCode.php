<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $room_code_id
 * @property string $code
 * @property int $active_players
 * @property string|null $player_x_name
 * @property string|null $player_o_name
 * @property int|null $player_x_id
 * @property int|null $player_o_id
 * @property Carbon|null $expires_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Game|null $game
 * @property-read Player|null $player_x
 * @property-read Player|null $player_y
 * @method static Builder<static>|RoomCode newModelQuery()
 * @method static Builder<static>|RoomCode newQuery()
 * @method static Builder<static>|RoomCode onlyTrashed()
 * @method static Builder<static>|RoomCode query()
 * @method static Builder<static>|RoomCode whereActivePlayers($value)
 * @method static Builder<static>|RoomCode whereCode($value)
 * @method static Builder<static>|RoomCode whereCreatedAt($value)
 * @method static Builder<static>|RoomCode whereDeletedAt($value)
 * @method static Builder<static>|RoomCode whereExpiresOn($value)
 * @method static Builder<static>|RoomCode wherePlayerOId($value)
 * @method static Builder<static>|RoomCode wherePlayerOName($value)
 * @method static Builder<static>|RoomCode wherePlayerXId($value)
 * @method static Builder<static>|RoomCode wherePlayerXName($value)
 * @method static Builder<static>|RoomCode whereRoomCodeId($value)
 * @method static Builder<static>|RoomCode whereUpdatedAt($value)
 * @method static Builder<static>|RoomCode withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|RoomCode withoutTrashed()
 * @mixin Eloquent
 */
class RoomCode extends Model
{
    use SoftDeletes;

    protected $table = 'room_codes';
    protected $primaryKey = 'room_code_id';

    protected $fillable = [
        'code',
        'expires_on',
        'active_players',
        'player_x_name',
        'player_o_name',
        'player_x_id',
        'player_o_id',
    ];

    protected $casts = [
        'expires_on' => 'date',
        'active_players' => 'int',
        'player_x_id' => 'int',
        'player_o_id' => 'int',
    ];

    public static function booted(): void
    {
        static::creating(function (self $model): void {
            $model->code = strtoupper(Str::random(4));
            $model->expires_on = Carbon::now()->addDay();
        });
    }

    public function game(): Builder|HasOne
    {
        return $this->hasOne(Game::class, 'room_code_id', 'room_code_id');
    }

    public function player_x(): HasOne
    {
        return $this->hasOne(Player::class, 'player_id', 'player_x_id');
    }

    public function player_y(): HasOne
    {
        return $this->hasOne(Player::class, 'player_id', 'player_y_id');
    }
}
