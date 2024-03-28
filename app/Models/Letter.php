<?php

namespace App\Models;

use App\Enums\PublishStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Miladimos\Toolkit\Traits\HasUUID;
use Miladimos\Toolkit\Traits\RouteKeyNameUUID;
use Cviebrock\EloquentSluggable\Sluggable;

class Letter extends Model
{
    use HasFactory,
        HasUUID,
        Sluggable,
        RouteKeyNameUUID;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('is_public', true);
    }

    public function scopeDelivered(Builder $query): void
    {
        $query->whereNot('delivered', '!=', null);
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('publish_status', PublishStatusEnum::PUBLISHED);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
