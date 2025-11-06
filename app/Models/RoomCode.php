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
 * @property string|null $expires_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder<static>|RoomCode newModelQuery()
 * @method static Builder<static>|RoomCode newQuery()
 * @method static Builder<static>|RoomCode query()
 * @method static Builder<static>|RoomCode whereCode($value)
 * @method static Builder<static>|RoomCode whereCreatedAt($value)
 * @method static Builder<static>|RoomCode whereDeletedAt($value)
 * @method static Builder<static>|RoomCode whereExpiresOn($value)
 * @method static Builder<static>|RoomCode whereRoomCodeId($value)
 * @method static Builder<static>|RoomCode whereUpdatedAt($value)
 * @property-read \App\Models\Game|null $game
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
    ];

    protected $casts = [
        'expires_on' => 'date',
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
}
