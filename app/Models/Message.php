<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\Uid\Uuid;

/**
 * @property int $email_id
 * @property string $slug
 * @property string $sender_name
 * @property string $from
 * @property string $original_from
 * @property string $original_to
 * @property string $to
 * @property array $cc
 * @property array $bcc
 * @property string $subject
 * @property string $body
 * @property Carbon $read_at
 * @property int $cloudflare_id
 * @property Mail $email
 */
class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $casts = [
        'cc' => 'array',
        'bcc' => 'array',
        'read_at' => 'datetime',
    ];

    protected $fillable = [
        'email_id',
        'slug',
        'sender_name',
        'from',
        'to',
        'original_from',
        'original_to',
        'cc',
        'bcc',
        'subject',
        'body',
        'read_at',
        'cloudflare_id',
        'otp_code',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = (string) Uuid::v7();
        });
    }

    public static function getInfolist(\Filament\Infolists\Infolist $infolist)
    {
        return $infolist
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextEntry::make('subject')->columnSpanFull(),
                        TextEntry::make('from'),
                        TextEntry::make('to'),
                        TextEntry::make('original_from'),
                        TextEntry::make('original_to'),
                        TextEntry::make('otp_code'),
                    ])
                    ->compact()
                    ->columns(2),
                Section::make('Body')
                    ->schema([
                        TextEntry::make('body')
                            ->alignment('center')
                            ->label('')
                            ->columnSpanFull()
                            ->html(),
                    ])
                    ->compact(),
            ]);
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Mail::class, 'email_id');
    }

    public function markAsSeen(): void
    {
        $this->read_at = now();
        $this->save();
    }
}
