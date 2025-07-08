<?php

namespace App\Http\Controllers;

use App\Http\Datas\ApiResponse;
use App\Http\Resources\MessageResource;
use App\Http\Resources\NetflixCodeResource;
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

        if ($request->user()->isAdmin()) {
            $query = Mail::with('messages')
                ->where('email', $request->input('email'))
                ->firstOrFail()
                ->messages();
        } else {
            $query = $request->user()
                ->mails()
                ->with('messages')
                ->where('email', $request->input('email'))
                ->firstOrFail()
                ->messages();
        }


        if ($request->input('withSeen', false) === false) {
            $query->where('read_at', null);
        }


        return ApiResponse::success(
            MessageResource::make(
                $query
                    ->select(['id', 'sender_name', 'from', 'to', 'subject', 'created_at', 'read_at'])
                    ->latest()
                    ->firstOrFail()
            )
        );
    }
}
