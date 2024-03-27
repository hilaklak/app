<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;
use Miladimos\Toolkit\Traits\RouteKeyNameUUID;

class FaqGroup extends Model
{
    use HasUUID,
        RouteKeyNameUUID,
        SoftDeletes;

    protected $table = 'faq_groups';

    // protected $fillable = ['title'];

    protected $guarded = [];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'group_id', 'id');
    }
}
