<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;

class Profile extends Model
{
    use HasUUID, SoftDeletes;

    protected $table = 'profiles';

    // protected $fillable = [
    //     'user_id', 'fname', 'lname', 'uuid', 'gender', 'site',
    //     'github', 'gitlab', 'bio', 'telegram', 'instagram', 'linkedin',
    //     'twetter', 'virgorl', 'atbox'
    // ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
