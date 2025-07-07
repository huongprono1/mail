<?php

namespace App\Rules;

use App\Models\Blacklist;
use App\Models\BlacklistHistory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckBlacklist implements ValidationRule
{
    protected ?string $blockedType = null;

    protected ?string $blockedValue = null;

    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $blockedItems = Blacklist::getBlockedItems();

        foreach ($blockedItems['keyword'] ?? [] as $item) {
            if (str_contains($value, $item['value'])) {
                $this->blockedType = 'keyword';
                $this->blockedValue = $item['value'];
                $fail(__('The :attribute has contain black list keyword: :keyword', [
                    'keyword' => $item['value'],
                    'attribute' => $attribute,
                ]));
                BlacklistHistory::create([
                    'blacklist_id' => $item['id'],
                    'content' => "$attribute: $value",
                ]);

                return;
            }
        }

        foreach ($blockedItems['domain'] ?? [] as $item) {
            if (str_contains($value, $item['value'])) {
                $this->blockedType = 'domain';
                $this->blockedValue = $item['value'];

                $fail(__('The :attribute has contain black list domain: :keyword', [
                    'keyword' => $item['value'],
                    'attribute' => str_replace('data.', '', $attribute),
                ]));
                BlacklistHistory::create([
                    'blacklist_id' => $item['id'],
                    'content' => "$attribute: $value",
                ]);

                return;
            }
        }
    }

    public function getBlockedType(): ?string
    {
        return $this->blockedType;
    }

    public function getBlockedValue(): ?string
    {
        return $this->blockedValue;
    }
}
