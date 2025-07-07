<?php

namespace App\Rules;

use App\Settings\SiteSettings;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedRegistrationDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedDomains = app(SiteSettings::class)->allowed_registration_domains ?? [];
        $domain = strtolower(substr(strrchr($value, '@'), 1));
        if (!in_array($domain, $allowedDomains)) {
            $fail(__('Registration with this email domain is not allowed.'));
        }
    }
}
