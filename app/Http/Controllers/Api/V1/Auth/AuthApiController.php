<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Site\Auth\UserLoggedIn;
use App\Http\Requests\UserLoginRequest;
use App\Events\Site\Auth\UserRegistered;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Controllers\Api\ApiBaseController;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class AuthApiController extends ApiBaseController
{
    public function me()
    {
        $user = Auth::user();

        return rJson(new UserResource($user), 'user detail', true, HttpStatus::HTTP_OK);
    }

    public function login(UserLoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return rJson('Credentials is invalid!', 'login failed', false, HttpStatus::HTTP_UNAUTHORIZED);
        }

        $user = User::findByEmail($request->email);

        if (!$user->isEmailVerified()) {
            return rJson('email not verified', 'email not verified', false, HttpStatus::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('Token')->plainTextToken;

        event(new UserLoggedIn($user));

        return rJson(['user' => new UserResource($user), 'token' => $token], 'user logged in', true, HttpStatus::HTTP_OK);
    }

    public function register(UserRegisterRequest $request)
    {

        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $user->profile()->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
            ]);

            $token = $user->createToken('TOKEN');

            event(new UserRegistered($user));

            DB::commit();

            return rJson(['token' => $token->plainTextToken], 'Email Verification link is sent.', true, HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();

            return rJson($e->getMessage(), 'User registration failed', false, HttpStatus::HTTP_BAD_REQUEST);
        }
    }

    public function verifyEmail(Request $request)
    {
        $code = $request->code;
        $activationCode = ActivationCode::where('code', $code)->first();

        if (!$activationCode) {
            // return redirect to frontend url
            return rJson('activation token is invalid', 'email verification failed', false, HttpStatus::HTTP_UNAUTHORIZED);
        }

        $user = $activationCode->user;

        if ($user->isEmailVerified()) {
            return rJson('your email already verified login again', 'your email already verified login again', true, HttpStatus::HTTP_OK);
        }

        $activationCode->user->verifyEmail();
        $activationCode->delete();

        // return redirect to frontend url
        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->logout();

        return Response::json('logout');
    }
}
