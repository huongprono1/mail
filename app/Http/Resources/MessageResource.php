<?php

namespace App\Http\Resources;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender_name' => $this->sender_name,
            'from' => $this->from,
            'to' => $this->to,
            'subject' => $this->subject,
            $this->mergeWhen($this->body, [
                'body' => $this->body,
            ]),
            'otp_code' => $this->otp_code ?? (new OtpService($this))->getOtpCode(),
            'read_at' => $this->read_at,
            'status' => is_null($this->read_at) ? 'unseen' : 'seen',
            'created_at' => $this->created_at,
        ];
    }
}
