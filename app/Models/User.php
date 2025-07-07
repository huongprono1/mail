<?php

namespace App\Models;

use App\Enums\UserPlanStatus;
use App\Observers\UserObserver;
use App\Services\UserFeatureService;
use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Glorand\Model\Settings\Contracts\SettingsManagerContract;
use Glorand\Model\Settings\Exceptions\ModelSettingsException;
use Glorand\Model\Settings\Traits\HasSettingsTable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;

/**
 * @property string $name
 * @property string $email
 * @property int $plan_id
 * @property int $telegram_id
 * @property HasOne $currentPlan
 * @property Mail[] $mails
 * @property SettingsManagerContract $settings
 * @property Plan[] $plans
 * @property bool hasPremium
 */
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, HasPasskeys, MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasSettingsTable, InteractsWithPasskeys, MustVerifyEmailTrait, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan_id',
        'telegram_id',
    ];

    protected array $searchable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        //        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        // Assuming the admin user has an ID of 1
        return $this->id === 1;
    }

    public function mails(): HasMany
    {
        return $this->hasMany(Mail::class); // ->chaperone();
    }

    public function messages(): HasManyThrough
    {
        // Message ← Mail ← User
        return $this->hasManyThrough(Message::class, Mail::class, 'user_id', 'email_id');
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        //        return $this->id === 1;
        return true;
    }

    /**
     * Specifies the user's FCM token
     *
     * @throws ModelSettingsException
     */
    public function routeNotificationForFcm(): array|string
    {
        return $this->settings()->get('fcm_tokens', []);
    }

    /**
     * Get the Telegram ID where notifications should be sent.
     */
    public function routeNotificationForTelegram(): int|string|null
    {
        return $this->telegram_id;
    }

    /**
     * @throws ModelSettingsException
     */
    public function updateFcmToken(string $new_token, ?string $old_token = null): void
    {
        $current_tokens = $this->settings()->get('fcm_tokens', []);
        // remove old token if exist
        if (filled($old_token) && in_array($old_token, $current_tokens)) {
            unset($current_tokens[$old_token]);
        }
        $current_tokens[] = $new_token;
        $this->settings()->set('fcm_tokens', $current_tokens);
    }

    /**
     * @throws BindingResolutionException
     */
    public function removeFcmToken(string $token): void
    {
        try {
            $current_tokens = $this->settings()->get('fcm_tokens', []);
            unset($current_tokens[$token]);
            $this->settings()->set('fcm_tokens', $current_tokens);
        } catch (\Exception $exception) {
            FilamentExceptions::report($exception);
        }
    }

    public function plans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function currentPlan(): HasOne
    {
        return $this->hasOne(UserPlan::class)
            ->where('status', UserPlanStatus::Active)
            ->where(function (Builder $query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->orderByDesc('started_at');
    }

    public function activePlan()
    {
        return $this->currentPlan()?->with('plan');
    }

    public function hasPremium(): bool
    {
        return $this->activePlan?->plan->getTranslation('name', 'en') === 'Premium';
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function personalAccessTokens(): HasMany
    {
        return $this->hasMany(\Laravel\Sanctum\PersonalAccessToken::class, 'tokenable_id')
            ->where('tokenable_type', self::class);
    }

    public function hasFeature(string $featureSlug): bool
    {
        // Tạo instance mới mỗi lần hoặc inject UserFeatureService nếu bạn dùng dependency injection ở đây
        return (new UserFeatureService($this))->hasFeature($featureSlug);
    }

    public function getFeatureValue(string $featureSlug, $default = null): int|bool|null
    {
        // Tạo instance mới mỗi lần hoặc inject UserFeatureService nếu bạn dùng dependency injection ở đây
        return (new UserFeatureService($this))->getFeatureValue($featureSlug, $default);
    }

    public function getLoadedFeatures(): \Illuminate\Support\Collection
    {
        return (new UserFeatureService($this))->getFeatures();
    }
}
