<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $code
 * @property string $link
 * @property string $real_email
 * @property string $origin_email
 * @property string $message
 * @property int $email_id
 * @property Mail $email
 * @property Carbon $read_at
 */
class NetflixCode extends Model
{
    protected $table = 'netflix_codes';

    protected $fillable = [
        'email_id',
        'real_email',
        'origin_email',
        'link',
        'code',
        'message',
        'read_at',
    ];

    public function email(): BelongsTo
    {
        return $this->belongsTo(Mail::class, 'email_id');
    }
}
