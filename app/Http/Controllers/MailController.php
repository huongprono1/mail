<?php

namespace App\Http\Controllers;

use App\Events\NewMessageEvent;
use App\Http\Datas\ApiResponse;
use App\Models\Mail;
use App\Rules\CheckBlacklist;
use App\Services\MailService;
use BezhanSalleh\FilamentExceptions\Facades\FilamentExceptions;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function checkMailExists(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $mail = Mail::query()->where('email', $request['email'])->first();
        if ($mail) {
            return response()->json([
                'exists' => true,
            ], 200);
        } else {
            return response()->json([
                'exists' => false,
            ], 200);
        }
    }

    public function storeMail(Request $request)
    {
        $validated = $request->validate(
            [
                'sender_name' => 'required|string|max:255',
                'from' => ['required', 'string', 'max:255', new CheckBlacklist],
                'to' => ['required', 'string', 'max:255', new CheckBlacklist],
                'subject' => ['required', 'string', 'max:255', new CheckBlacklist],
                'raw_body' => 'required',
            ]
        );

        try {
            // search mail exist
            $email = Mail::query()->where('email', $validated['to'])->first();
            if (! $email) {
                return ApiResponse::error('Received email not found', 400);
            }

            $mailService = new MailService($email, $validated['raw_body'], $validated['from'], $validated['to']);
            $message = $mailService->storeMessage();

            event(new NewMessageEvent($email, $message));

            return ApiResponse::error('Saved mail.', 200);
        } catch (\Exception $e) {
            FilamentExceptions::report($e);

            return ApiResponse::error($e->getMessage(), 500);
        }
    }
}
