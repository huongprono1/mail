<?php

namespace App\Models;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property Mail[] $mails
 * @property string $session_id
 * @property string $ip_address
 * @property string $user_agent
 * @property array $additional_info
 * @property string $country
 * @property string $city
 * @property string $state
 * @property string $browser
 * @property string $device
 * @property string $platform
 */
class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'additional_info',
        'country',
        'city',
        'state',
        'browser',
        'device',
        'platform',
    ];

    protected $casts = [
        'additional_info' => 'array',
    ];

    /**
     * Many-to-Many relationship: A session can have many mails.
     */
    public function mails(): BelongsToMany
    {
        return $this->belongsToMany(Mail::class, 'client_mail')->withTimestamps();
    }

    public static function getInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('session_id'),
                TextEntry::make('ip_address')->copyable(),
                TextEntry::make('user_agent')->copyable(),
                TextEntry::make('country'),
                TextEntry::make('city'),
                TextEntry::make('state'),
                TextEntry::make('created_at'),
                TextEntry::make('updated_at'),
            ]);
    }
}
