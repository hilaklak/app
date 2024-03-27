<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;
use Miladimos\Toolkit\Traits\RouteKeyNameUUID;

class AcquaintedUs extends Model
{
    use HasUUID,
        RouteKeyNameUUID,
        SoftDeletes;

    protected $table = 'acquainted_us';

    protected $guarded = [];
}
