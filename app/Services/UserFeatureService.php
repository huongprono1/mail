<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UserFeatureService
{
    protected User $user;

    protected ?Plan $plan = null;

    protected string $cacheKey;

    protected int $cacheDuration; // tính bằng phút

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->cacheKey = "user_features_{$this->user->id}";
        $this->plan = $user->currentPlan ? $user->currentPlan->plan : null;
        $this->cacheDuration = 60;
    }

    public function getFeatureValue(string $featureSlug, int|bool|null $default = null): int|bool|null
    {
        if (! $this->plan) {
            return $default;
        }

        if ($this->hasFeature($featureSlug)) {
            $value = $this->getFeatures()->get($featureSlug);
            if (is_numeric($value)) {
                return intval($value);
            }

            return filter_var($value ?? '', FILTER_VALIDATE_BOOLEAN);
        }

        return $default;
    }

    /**
     * Kiểm tra xem user có một feature cụ thể hay không.
     */
    public function hasFeature(string $featureSlug): bool
    {
        return $this->getFeatures()->has($featureSlug);
    }

    /**
     * Xóa cache features của user.
     * Gọi hàm này khi plan của user thay đổi hoặc features của user được cập nhật.
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * "Làm nóng" cache bằng cách tải và lưu trữ features.
     */
    public function warmCache(): void
    {
        $this->clearCache(); // Xóa cache cũ nếu có
        $this->getFeatures(); // Tải và lưu vào cache
    }

    /**
     * Lấy tất cả features của user (từ cache hoặc database).
     * Trả về một Collection các slug của feature.
     */
    public function getFeatures(): Collection
    {
        if (! $this->plan) {
            return collect();
        }

        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            return $this->plan->planFeatures->pluck('value', 'feature.key');

            // Nếu features có thể được gán trực tiếp cho User (ngoài Plan)
            // hoặc ghi đè features của Plan, logic ở đây sẽ phức tạp hơn để hợp nhất chúng.
            // Ví dụ:
            // $planFeatures = $this->user->plan ? $this->user->plan->features->pluck('slug') : collect();
            // $userSpecificFeatures = $this->user->directFeatures->pluck('slug'); // Giả sử có relationship user->directFeatures
            // return $planFeatures->merge($userSpecificFeatures)->unique();
        });
    }
}
