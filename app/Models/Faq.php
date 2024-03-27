<?php

namespace App\Models;

use App\Enums\FaqTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;
use Miladimos\Toolkit\Traits\RouteKeyNameUUID;

class Faq extends Model
{
    use HasUUID,
        RouteKeyNameUUID,
        SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = ['question', 'answer', 'uuid', 'group_id', 'course_id', 'type'];

    protected $dates = ['deleted_at'];

    public function group()
    {
        return $this->belongsTo(FaqGroup::class);
    }

    public function type()
    {
        switch ($this->type) {
            case FaqTypeEnum::SITE:
                return 'سایت';
                break;
            case FaqTypeEnum::COURSE:
                return 'دوره';
                break;

            default:
                // code...
                break;
        }
    }
}
