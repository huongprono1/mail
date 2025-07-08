<?php

namespace App\Http\Controllers;

use App\Http\Datas\ApiResponse;
use App\Http\Resources\MessageResource;
use App\Http\Resources\NetflixCodeResource;
use App\Models\Domain;
use App\Models\Mail;
use Illuminate\Http\Request;

class ApiMailController extends Controller
{
    public function getMessageOfMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'withSeen' => 'boolean|nullable',
        ]);

        $email = $request->input('email');
        // check domain in mail exist
        $domain = Domain::where('name', get_domain_from_email($email))->first();
        if (!$domain) {
            return ApiResponse::error('Domain not found.', 404);
        }
        // check mail exist in database
        $mail = Mail::where('email', $email)->first();
        if (!$mail) {
            // if not exist, create new mail
            $request->user()->mails()->firstOrCreate([
                'email' => $email,
            ], [
                'domain_id' => $domain->id, // Assuming domain_id is not required for this operation
                'user_id' => $request->user()->id,
                'created_at' => now()
            ]);
        }

        if ($mail->isOwnedBy($request->user()) || $request->user()->isAdmin()) {
            $query = Mail::with('messages')
                ->where('email', $email)
                ->firstOrFail()
                ->messages();
        } else {
            $query = $request->user()
                ->mails()
                ->with('messages')
                ->where('email', $email)
                ->firstOrFail()
                ->messages();
        }


        if ($request->input('withSeen', false) === false) {
            $query->where('read_at', null);
        }

        $message = $query
            ->select(['id', 'sender_name', 'from', 'to', 'subject', 'created_at', 'read_at', 'body'])
            ->latest()
            ->first();
        if (!$message) {
            return ApiResponse::error('No messages found for ' . $email, 404);
        }

        // mark mail seen
        if (is_null($message->read_at)) {
            $message->markAsSeen();
        }

        return ApiResponse::success(
            MessageResource::make(
                $message
            )
        );
    }
}
