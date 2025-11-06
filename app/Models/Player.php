<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $player_id
 * @property string $name
 * @property string|null $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder<static>|Player newModelQuery()
 * @method static Builder<static>|Player newQuery()
 * @method static Builder<static>|Player query()
 * @method static Builder<static>|Player whereCreatedAt($value)
 * @method static Builder<static>|Player whereDeletedAt($value)
 * @method static Builder<static>|Player whereEmail($value)
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
    ];
}
