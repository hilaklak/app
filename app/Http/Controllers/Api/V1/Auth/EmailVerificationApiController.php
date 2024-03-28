<?php

namespace App\Http\Controllers\Api\v1\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Enums\ActivationCodeTypeEnum;
use App\Mail\Auth\EmailVerificationMale;
use App\Http\Controllers\Api\ApiBaseController;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class EmailVerificationApiController extends ApiBaseController
{
    public function sendEmailVerification()
    {
        $user = Auth::user();

        if ($user->isEmailActivated()) {
            return rJson('ایمیل شما تایید شده می باشد.', 'ایمیل شما تایید شده می باشد.', true, HttpStatus::HTTP_OK);
        }

        if ($user->activationCodes()->count() > 0) {
            $user->activationCodes()->first()->delete();
        }

        $code = $user->activationCodes()->create([
            'code' => Str::random(32),
            'expired_at' => now()->addMinutes(2),
            'type' => ActivationCodeTypeEnum::EMAIL,
        ]);

        if ($code) {
            $url = route('user.account.verification.email.verify', ['token' => $code->code]);

            Mail::to($user)->send(new EmailVerificationMale($url));

            return rJson('ایمیل حاوی لینک فعال سازی ارسال شد.', 'ایمیل حاوی لینک فعال سازی ارسال شد.', true, HttpStatus::HTTP_OK);
        }

        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);
    }

    public function verifyEmailVerification(Request $request)
    {
        $token = $request->token;

        $user = user();
        $code = $user->activationCodes()->where(['code' => $token, 'type' => ActivationCodeTypeEnum::EMAIL])->first();

        if (!$code) {
            return rJson('لینک تایید سازی معتبر نمی باشد.', 'لینک تایید سازی معتبر نمی باشد.', false, HttpStatus::HTTP_OK);
        }

        $user->metas()->update([
            'email_verified_at' => now(),
        ]);

        $code->delete();

        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);
    }
}
