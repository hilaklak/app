<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $table = 'users';

    protected $with = ['profile'];
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function letters()
    {
        return $this->hasMany(Letter::class, 'user_id', 'id');
    }
    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function metas()
    {
        return $this->morphOne(UserMeta::class, 'metaable');
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
        return $this->id === user()->id;
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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
            $username = 'snj_' . substr($shuf, 0, 8);
            $exist = true;

            if (!static::where('username', $username)->exists() && static::where('username', null)) {
                $exist = false;

                return $username;
            }
        } while ($exist);
    }
}
