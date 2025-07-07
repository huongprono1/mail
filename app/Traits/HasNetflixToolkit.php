<?php

namespace App\Traits;

use App\Models\Message;
use App\Models\NetflixCode;
use BezhanSalleh\FilamentExceptions\Facades\FilamentExceptions;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait HasNetflixToolkit
{
    private array $title_arrays = [
        'Your Netflix temporary access code',
        'How to update your Netflix Household',
        'Mã truy cập Netflix tạm thời của bạn',
        'Cách cập nhật Hộ gia đình Netflix',
    ];

    /**
     * @throws InvalidSelectorException
     */
    protected function processNetflix(Message $message): void
    {
        foreach ($this->title_arrays as $title) {
            if (Str::contains($message->subject, $title)) {
                $this->processHousehold($message);
                break;
            }
        }

    }

    /**
     * @throws InvalidSelectorException
     */
    private function processHousehold(Message $message): void
    {
        try {
            $doc = new Document($message->body);

            $a = $doc->first('a.h5[href]');

            $url = $a->getAttribute('href');

            $response = Http::withOptions([
                'cookies' => new \GuzzleHttp\Cookie\CookieJar,
                'allow_redirects' => true,
            ])
                ->withUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36')
                ->get($url);
            //
            if ($response->successful()) {
                $netDom = new Document($response->body());
                //                Storage::put("netflix.html", $response->body());
                // netflix code
                $title = $netDom->first('div.title')?->text();
                $code = $netDom->first('div.challenge-code')?->text();

                // household
                if (! $title) {
                    $title = $netDom->first('h1')?->text();
                }

                NetflixCode::query()->create([
                    'real_email' => $message->to,
                    'origin_email' => $message->original_to,
                    'link' => $url,
                    'code' => $code,
                    'message' => $title,
                    'email_id' => $message->email_id,
                ]);
            } else {
                Log::error('Get request netflix response not successful. Status code :code', ['code' => $response->status()]);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            FilamentExceptions::report($e);
            echo $e->getTraceAsString();
        }
    }
}
