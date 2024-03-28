<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\ActivationCodeTypeEnum;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\User\Auth\VerificationEmailNotification;
use App\Notifications\User\Auth\ResetPasswordEmailRequestNotification;
use Miladimos\Toolkit\Traits\HasUUID;

class User extends Authenticatable
{
    use HasFactory,
        Notifiable,
        HasUUID;

    protected $table = 'users';

    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['profile'];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function metas()
    {
        return $this->morphOne(UserMeta::class, 'metaable');
    }

    public function letters()
    {
        return $this->hasMany(Letter::class, 'user_id', 'id');
    }

    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class);
    }

    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function markNotificationAsRead($notification_id)
    {
        $notification = $this->notifications()
            ->where('id', $notification_id)
            ->whereNull('read_at');

        if ($notification->exists()) {
            $notification->first()->markAsRead();
        }
    }

    public function isLoggedInUser(): bool
    {
        return $this->id === Auth::user()->id;
    }

    public function path()
    {
        return "/@{$this->username}";
    }

    public function url()
    {
        return url($this->path());
    }

    public function isMobileVerified()
    {
        return (bool) $this->metas->mobile_verified_at;
    }

    public function verifyMobile()
    {
        return (bool) $this->metas()->update([
            'mobile_verified_at' => now(),
        ]);
    }

    public function isEmailVerified()
    {
        return (bool) $this->metas->email_verified_at;
    }

    public function verifyEmail()
    {
        return (bool) $this->metas()->update([
            'email_verified_at' => now(),
        ]);
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $password) => bcrypt($password)
        );
    }

    public function getFullNameAttribute()
    {
        return $this->profile->fname . ' ' . $this->profile->lname;
    }

    public function getLabelAttribute()
    {
        return $this->profile->fname . ' ' . $this->profile->lname . ' - ' . $this->username;
    }

    public function getAvatarAttribute()
    {
        return isset($this->profile->avatar) ? asset($this->profile->avatar) : asset('/statics/avatars/default.png');
    }

    public function generateUserToken($token)
    {
        return Crypt::encryptString($token);
    }

    public function generateEmailVerificationToken()
    {
        return ActivationCode::create([
            'user_id' => $this->id,
            'expired_at' => now()->addMinutes(30),
            'code' => Str::random(64),
            'type' => ActivationCodeTypeEnum::EMAIL,
        ]);
    }

    public function generateMobileVerificationToken()
    {
        return ActivationCode::create([
            'user_id' => $this->id,
            'expired_at' => now()->addMinutes(30),
            'code' => random_int(000000, 999999),
            'type' => ActivationCodeTypeEnum::MOBILE,
        ]);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerificationEmailNotification());
    }

    public function sendResetPasswordEmailRequestNotification()
    {
        $this->notify(new ResetPasswordEmailRequestNotification());
    }

    // public function sendResetPasswordMobileRequestNotification()
    // {
    //     $this->notify(new ResetPasswordMobileRequestNotification());
    // }

    public static function generateUsername()
    {
        do {
            $digits = array_merge(range(0, 9), range(0, 9), range(0, 9));
            $sChars = range('a', 'z');
            $cChars = range('A', 'Z');
            $chars = array_merge($digits, $sChars, $cChars);
            $arrToStr = implode('', $chars);
            $shuf = str_shuffle($arrToStr);
            $username = 'hlk_' . substr($shuf, 0, 8);
            $exist = true;

            if (!static::where('username', $username)->exists() && static::where('username', null)) {
                $exist = false;

                return $username;
            }
        } while ($exist);
    }
}
