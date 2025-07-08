<?php

namespace App\Services;

use App\Models\EmailOtpRegexRule;
use App\Models\Message;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class OtpService
{
    public Message|JsonResource $message;

    public function __construct(Message|JsonResource $message)
    {
        $this->message = $message;
    }

    public function getOtpCode(): ?string
    {
        $otp = null;
        // get 1st domain from 'from' of $message
        $domain = explode('@', $this->message->from)[1];
        // auto regex
        $mailOtpRegexps = EmailOtpRegexRule::query()->where('sender_domain', $domain)->get();
        foreach ($mailOtpRegexps as $mailOtpRegex) {
            $pattern = $mailOtpRegex->regex_pattern;
            // check $mailOtpRegex->regex_pattern contain / and / in before and after
            if (! Str::startsWith($mailOtpRegex->regex_pattern, '/') && ! Str::endsWith($mailOtpRegex->regex_pattern, '/')) {
                $pattern = '/'.$mailOtpRegex->regex_pattern.'/';
            }

            if (preg_match($pattern, $this->message->body, $matches)) {
                $otp = $matches[1] ?? null;
            }
        }
        if (is_null($otp)) {
            $otp = $this->convertToTextAndGetOtpCode($this->message->body);
        }

        return $otp;
    }

    /**
     * @throws InvalidSelectorException
     */
    private function convertToTextAndGetOtpCode($html): ?string
    {
        $dom = new Document($html);
        $text = $html;
        if ($dom->first('body')) {
            $text = $dom->first('body')->text();
        }
        $otp = null;
        // auto regex get otp for 5-6 digits in text
        if (preg_match('/\d{5,6}/', $text, $matches)) {
            $otp = $matches[0] ?? null;
        }

        return $otp;
    }
}
