<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property User $user
 * @property integer $user_id
 * @property string $name
 * @property string $hash_name
 * @property string $original_name
 * @property string $url
 * @property string $size
 * @property string $mime
 *
 * @property Carbon|string $created_at
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'hash_name',
        'original_name',
        'url',
        'size',
        'mime'
    ];

    protected $guarded = [
        'id'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
