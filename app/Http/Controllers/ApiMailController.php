<?php

namespace App\Http\Controllers;

use App\Http\Datas\ApiResponse;
use App\Http\Resources\DomainResource;
use App\Http\Resources\MailResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\NetflixCodeResource;
use App\Models\Domain;
use App\Models\Mail;
use App\Models\Message;
use App\Models\NetflixCode;
use App\Models\User;
use BezhanSalleh\FilamentExceptions\Facades\FilamentExceptions;
use Exception;
use Glorand\Model\Settings\Exceptions\ModelSettingsException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ApiMailController extends Controller
{
    public function getMessageOfMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'withSeen' => 'boolean|nullable',
        ]);

        $query = $request->user()
            ->mails()
            ->with('messages')
            ->where('email', $request->input('email'))
            ->firstOrFail()
            ->messages();


        if ($request->input('withSeen', false) === false) {
            $query->where('read_at', null);
        }


        return ApiResponse::success(
            MessageResource::make(
                $query
                    ->select(['id', 'sender_name', 'from', 'to', 'subject', 'created_at', 'read_at'])
                    ->latest()
                    ->first()
            )
        );
    }
}
