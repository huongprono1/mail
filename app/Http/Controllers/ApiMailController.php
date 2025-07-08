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

        // auto generate the email if it is not provided
        $email = $request->input('email');
        auth()->user()->mails()->firstOrCreate([
            'email' => $email,
        ], [
            'domain_id' => Domain::where('name', get_domain_from_email($email)), // Assuming domain_id is not required for this operation
            'user_id' => $request->user()->id,
            'created_at' => now()
        ]);

        if ($request->user()->isAdmin()) {
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
            return ApiResponse::error('No messages found for this email.', 404);
        }


        return ApiResponse::success(
            MessageResource::make(
                $message
            )
        );
    }
}
