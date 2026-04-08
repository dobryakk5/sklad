<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ForgotPasswordRequest;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Mail\OperatorResetPasswordMail;
use App\Models\Operator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class AuthController extends AdminController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $operator = Operator::query()->where('email', $request->string('email'))->first();

        if (! $operator || ! Hash::check($request->string('password'), $operator->password)) {
            return response()->json(['error' => 'Неверный email или пароль'], 401);
        }

        $token = Str::random(64);
        $operator->update(['token' => $token]);

        return $this->item([
            'token' => $token,
            'name' => $operator->name,
            'email' => $operator->email,
            'role' => $operator->role,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->operator($request)->update(['token' => null]);

        return response()->json(['message' => 'Logged out']);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $email = $request->string('email')->toString();
        $operator = Operator::query()->where('email', $email)->first();

        if (! $operator) {
            return response()->json(['message' => 'Если аккаунт существует, письмо отправлено']);
        }

        DB::table('operator_password_resets')->where('email', $email)->delete();

        $token = Str::random(64);

        DB::table('operator_password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = rtrim((string) config('app.frontend_url'), '/')
            . '/admin/reset-password?token='
            . urlencode($token)
            . '&email='
            . urlencode($email);

        Mail::to($email)->send(new OperatorResetPasswordMail($resetUrl));

        return response()->json(['message' => 'Если аккаунт существует, письмо отправлено']);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $email = $request->string('email')->toString();
        $record = DB::table('operator_password_resets')->where('email', $email)->first();

        if (! $record) {
            return response()->json(['error' => 'Недействительная ссылка'], 422);
        }

        if (Carbon::parse($record->created_at)->diffInMinutes(now()) > 60) {
            DB::table('operator_password_resets')->where('email', $email)->delete();

            return response()->json(['error' => 'Ссылка устарела. Запросите новую'], 422);
        }

        if (! Hash::check($request->string('token'), $record->token)) {
            return response()->json(['error' => 'Недействительная ссылка'], 422);
        }

        $operator = Operator::query()->where('email', $email)->first();

        if (! $operator) {
            return response()->json(['error' => 'Аккаунт не найден'], 422);
        }

        $operator->update([
            'password' => $request->string('password')->toString(),
            'token' => null,
        ]);

        DB::table('operator_password_resets')->where('email', $email)->delete();

        return response()->json(['message' => 'Пароль успешно изменён']);
    }
}
