<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Enums\ForgotPasswordTypeEnum;
use App\Events\Auth\UserForgotPassword;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Requests\ForgotPasswordEmailRequest;
use App\Http\Requests\ForgotPasswordMobileRequest;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class ForgotPasswordApiController extends ApiBaseController
{
    public function forgotPasswordEmail(ForgotPasswordEmailRequest $request)
    {

        $user = User::findByEmail($request->email);
        if (!$user) {
            return rJson('email not found', 'email not found', false, HttpStatus::HTTP_NOT_FOUND);
        }

        $token = base64_encode(Str::random(60));
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $token,
                'type' => ForgotPasswordTypeEnum::EMAIL,
            ]
        );

        if ($user && $passwordReset) {

            event(new UserForgotPassword($user, ForgotPasswordTypeEnum::EMAIL, $token));

            return rJson('reset link sent', 'reset link sent', true, HttpStatus::HTTP_OK);
        }
    }

    public function resetPasswordEmail(Request $request)
    {
        $passwordReset = PasswordReset::where([
            'token' => $request->token,
            'email' => $request->email,
        ])->first();

        if (!$passwordReset) {
            return rJson('token invalid', 'token invalid', false, HttpStatus::HTTP_NOT_FOUND);
        }

        $user = User::findByEmail($request->email);
        if (!$user) {
            return rJson("We can't find a user with that e-mail address.", "We can't find a user with that e-mail address.", false, HttpStatus::HTTP_NOT_FOUND);
        }

        $user->update(['password' => $request->password]);
        $user->metas()->update(['password_changed_at' => now()]);

        $passwordReset->delete();

        //        $user->notify(new UserResetPasswordSuccess($passwordReset));

        return rJson('password changed', 'password changed', true, HttpStatus::HTTP_OK);
    }

    public function forgotPasswordMobile(ForgotPasswordMobileRequest $request)
    {

        $user = User::findByMobile($request->mobile);
        if (!$user) {
            return rJson('mobile not found', 'mobile not found', false, HttpStatus::HTTP_NOT_FOUND);
        }

        $token = random_int(6, 6);
        $passwordReset = PasswordReset::updateOrCreate(
            ['mobile' => $user->mobile],
            [
                'mobile' => $user->mobile,
                'token' => $token,
                'type' => ForgotPasswordTypeEnum::EMAIL,
            ]
        );

        if ($user && $passwordReset) {

            event(new UserForgotPassword($user, ForgotPasswordTypeEnum::MOBILE, $token));

            return rJson('token sms to your mobile number', 'token sms to your mobile number', true, HttpStatus::HTTP_OK);
        }
    }

    public function resetPasswordMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string|min:6|max:6',
            'mobile' => 'required|string|digits_between:10,11',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return rJson($validator->errors(), 'validation error', false, HttpStatus::HTTP_BAD_REQUEST);
        }

        $passwordReset = PasswordReset::where([
            'token' => $request->token,
            'mobile' => $request->mobile,
        ])->first();

        if (!$passwordReset) {
            return rJson('token invalid', 'token invalid', false, HttpStatus::HTTP_NOT_FOUND);
        }

        $user = User::findByMobile($request->mobile);
        if (!$user) {
            return rJson("We can't find a user with that mobile.", "We can't find a user with that mobile.", false, HttpStatus::HTTP_NOT_FOUND);
        }

        $user->update(['password' => $request->password]);
        $user->metas()->update(['password_changed_at' => now()]);

        $passwordReset->delete();

        //        $user->notify(new UserResetPasswordSuccess($passwordReset));

        return rJson('password changed', 'password changed', true, HttpStatus::HTTP_OK);
    }
}
