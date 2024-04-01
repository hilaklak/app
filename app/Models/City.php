<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;

class City extends Model
{
    use HasUUID, Sluggable, SoftDeletes;

    protected $table = 'cities';

    protected $fillable = ['title', 'slug', 'province_id', 'uuid'];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
}
