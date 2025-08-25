<?php

namespace App\Models;

use App\Traits\HasMailable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $domain_id
 * @property int $user_id
 * @property Domain $domain
 * @property string $email
 *
 * @method Builder withActiveDomain
 *
 * @property NetflixCode[] $netflixCodes
 * @property Message[] $messages
 */
class Mail extends Model
{
    use HasFactory, HasMailable, SoftDeletes;
    use LogsActivity;

    protected $table = 'mails';

    protected $fillable = [
        'user_id',
        'email',
        'domain_id',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_mail')->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'email_id');
    }

    public function netflixCodes(): HasMany
    {
        return $this->hasMany(NetflixCode::class, 'email_id');
    }

    public function isOwnedBy(Client|User $client): bool
    {
        // if ($client instanceof User) {
        //     return $client->isAdmin() || $this->user_id == $client->id;
        // } else {
        //     return $client->mails()->find($this->id) != null;
        // }
        return true;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['user_id', 'deleted_at']);
    }

    /**
     * Scope a query to only include mails with active domains.
     */
    public function scopeWithActiveDomain(Builder $query): Builder
    {
        return $query->whereHas('domain', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Get all mails with active domains matching the search term.
     */
    public static function getMailWithDomainActive(string $email): ?Mail
    {
        return static::with('domain')
            ->withActiveDomain()
            ->when($email, function ($query) use ($email) {
                return $query->where('email', $email);
            })->first();
    }
}
