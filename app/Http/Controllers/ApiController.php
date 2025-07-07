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

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Invalid credentials', 401);
        }
        // Xóa token cũ có cùng tên 'app_auth_token'
        //        $user->tokens()->where('name', 'app_auth_token')->delete();

        $token = $user->createToken('app_auth_token')->plainTextToken;

        return ApiResponse::success([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        return ApiResponse::success(User::create($validate));
    }

    public function getUser(Request $request)
    {
        return ApiResponse::success($request->user());
    }

    public function getDomains()
    {
        return ApiResponse::success(DomainResource::collection(Domain::accessible()->get()));
    }

    public function getEmails(Request $request)
    {
        return ApiResponse::success(MailResource::collection($request->user()->mails));
    }

    /**
     * @throws BindingResolutionException
     */
    public function createEmail(Request $request)
    {
        try {
            if ($request->json('user') && $request->json('domain')) {
                $mail = app(Mail::class)->newCustomMail($request->json('user'), $request->json('domain'));
            } else {
                $mail = app(Mail::class)->newRandomMail();
            }

            return ApiResponse::success(MailResource::make($mail));
        } catch (Exception $e) {
            FilamentExceptions::report($e);

            return ApiResponse::error($e->getMessage());
        }
    }

    public function getEmailMessages(Request $request, $id)
    {
        return ApiResponse::success(
            MessageResource::collection(
                $request->user()
                    ->mails()
                    ->with('messages')
                    ->findOrFail($id)
                    ->messages()
                    ->select('id', 'sender_name', 'from', 'to', 'subject', 'created_at', 'read_at')
                    ->orderByDesc('id')
                    ->paginate(10)
            )
        );
    }

    public function getMessage(Request $request, $id)
    {
        $message = Message::query()->findOrFail($id);
        if (!$message->email->isOwnedBy($request->user())) {
            abort(403, __('Access denied. You do not own this email.'));
        }

        if (is_null($message->read_at)) {
            defer(function () use ($message) {
                $message->read_at = now();
                $message->save();
            });
        }

        return ApiResponse::success(MessageResource::make($message));
    }

    public function getNetflixCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $email = $request->email;

        $netflix = NetflixCode::query()
            ->where(function ($query) use ($email) {
                $query->where('real_email', $email)->orWhere('origin_email', $email);
            })
            ->where('created_at', '>', now()->subMinutes(15))
            ->orderByDesc('created_at')
            ->first();

        if (!$netflix) {
            return ApiResponse::error('Netflix code not found.');
        }
        if (!$netflix->email->isOwnedBy($request->user())) {
            abort(403, 'Access denied.');
        }

        if (is_null($netflix->read_at)) {
            defer(function () use ($netflix) {
                $netflix->read_at = now();
                $netflix->save();
            });
        }

        return ApiResponse::success(NetflixCodeResource::make($netflix));
    }

    /**
     * MANAGER FCM TOKENs
     *
     * @throws ModelSettingsException
     */
    public function updateFcmToken(Request $request)
    {
        $data = $request->validate([
            'fcm_token' => 'required|string',
            'old_fcm_token' => 'nullable|string',
        ]);

        $user = $request->user();
        try {
            $user->updateFcmToken($data['fcm_token'], $data['old_fcm_token'] ?? null);
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage());
        }

        return ApiResponse::success(message: 'Update token success');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? ApiResponse::success(message: 'Email đặt lại mật khẩu đã được gửi.')
            : ApiResponse::error(message: 'Không thể gửi email.');
    }
}
