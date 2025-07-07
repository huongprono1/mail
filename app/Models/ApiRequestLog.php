<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class ApiRequestLog extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'path',
        'route_name',
        'query_params',
        'request_headers',
        'request_body',
        'status_code',
        'response_headers',
        'response_content',
        'ip_address',
        'user_agent',
        'execution_time',
        'error_message',
        'additional_data',
    ];

    protected $casts = [
        'query_params' => 'array',
        'request_headers' => 'array',
        'request_body' => 'array',
        'response_headers' => 'array',
        'additional_data' => 'array',
        'execution_time' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an API request and its response
     *
     * @param null $response
     * @return static
     */
    public static function log(
        Request $request,
        $response = null,
        ?float $startTime = null,
        ?string $errorMessage = null
    ): self
    {
        $executionTime = $startTime ? (microtime(true) - $startTime) * 1000 : null;

        $log = new static([
            'user_id' => optional($request->user())->id,
            'method' => $request->method(),
            'path' => '/' . ltrim($request->path(), '/'),
            'route_name' => $request->route() ? $request->route()->getName() : null,
            'query_params' => $request->query->all(),
            'request_headers' => collect($request->headers->all())
                ->map(fn($header) => $header[0] ?? null)
                ->toArray(),
            'request_body' => $request->except(['password', 'password_confirmation', 'current_password']),
            'status_code' => $response?->status(),
            'response_headers' => $response ? collect($response->headers->all())
                ->map(fn($header) => $header[0] ?? null)
                ->toArray() : null,
            //            'response_content' => $response ? $response->getContent() : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'execution_time' => $executionTime,
            'error_message' => $errorMessage,
        ]);

        $log->save();

        return $log;
    }
}
