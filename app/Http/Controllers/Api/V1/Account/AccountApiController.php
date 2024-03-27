<?php

namespace App\Http\Controllers\Api\V1\Account;

use Exception;
use App\Models\Order;
use App\Models\Course;
use App\Enums\GenderEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountSocialsRequest;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Academy\Course\CourseCollection;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class AccountApiController extends ApiBaseController
{
    public function updateInformation(Request $request)
    {
        $user = Auth::user();

        $user->update([
            'username' => $request->username,
        ]);

        // $avatar_path = $user->profile->avatar_path;

        // if ($request->hasFile('avatar_path')) {
        //     $avatar_path = $this->uploadOneImage($request->file('avatar_path'), 'users/avatars');
        // }

        $user->profile()->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender ?? GenderEnum::UNKNOWN,
            'bio' => $request->bio,
            // 'birthday' => $request->birthday,
            // 'avatar_path' => $avatar_path,
        ]);

        return rJson('اطلاعات حساب کاربری با موفقیت بروز ش', 'اطلاعات حساب کاربری با موفقیت بروز ش', true, HttpStatus::HTTP_OK);
    }

    public function updateSocials(AccountSocialsRequest $request)
    {
        $user = Auth::user();

        $user->profile()->update([
            'site' => $request->site,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
            'telegram' => $request->telegram,
            'github' => $request->github,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
        ]);

        return rJson('اطلاعات شبکه های اجتماعی با موفقیت بروز شد', 'اطلاعات شبکه های اجتماعی با موفقیت بروز شد', true, HttpStatus::HTTP_OK);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'old_password' => [
                'required',
                function ($attr, $value, $fall) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fall('current password is incorrect');
                    }
                },
            ],
            'password' => 'required|min:8|max:100',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($request->password == $request->old_password) {
            return Response::back('رمز عبور حدید نمیتواند با رمز عبور جدید یکسان باشد..', 'error');
        }

        $user->update([
            'password' => $request->password,
        ]);

        $user->metas()->update([
            'password_changed_at' => now(),
        ]);

        return rJson('رمز عبور با موفقیت تغییر کرد', 'رمز عبور با موفقیت تغییر کرد', true, HttpStatus::HTTP_OK);
    }


    public function letters()
    {
        $letters = Auth::user()->letters()->latest()->paginate(24);

        return rJson($letters, 'my downloaded letters', true, HttpStatus::HTTP_OK);
    }


    public function updateNotifications(Request $request)
    {
        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);
    }

    public function sendMobileVerificationCode(Request $request)
    {
        $mobile = $request->mobile;

        $user = Auth::user();

        if ($user->isMobileActivated($mobile)) {
            return back()->with([
                'message' => 'این شماره فعال شده است',
                'type' => 'success',
            ]);
        }

        if ($user->activationCodes()->count() > 0) {
            $user->activationCodes()->first()->delete();
        }

        $code = $user->activationCodes()->create([
            'code' => random_int(111111, 999999),
            'expired_at' => now()->addMinutes(2),
        ]);

        if ($user->mobile !== $mobile) {
            $user->update([
                'mobile' => $mobile,
                'mobile_verified_at' => null,
            ]);
        }

        try {
            RayganSMSService::SendOTP($mobile, $code['code']);
        } catch (Exception $e) {
        }

        Session::put(
            'verify',
            ['code' => $code->code],
        );

        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);

        return redirect()->back()->with([
            'message' => 'کد فعال سازی ارسال شد',
            'type' => 'success',
        ]);
    }

    public function mobileVerify(Request $request)
    {
        $code = $request->code;

        $user = Auth::user();

        if ($user->activationCodes()->where('code', $code)->first()) {
            $user->update([

                'mobile_verified_at' => now(),
            ]);

            Session::remove('verify');

            return redirect()->back()->with([
                'message' => 'شماره فعال شد',
                'type' => 'success',
            ]);
        }

        Session::remove('verify');

        return rJson('Your e-mail is verified. You can now login.', 'Your e-mail is verified. You can now login.', true, HttpStatus::HTTP_OK);

        return redirect()->back()->with([
            'message' => 'کد وارد شده صحیح نمی باشد از نو درخواست دهید',
            'type' => 'success',
        ]);
    }
}
