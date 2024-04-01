<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;

class Province extends Model
{
    use HasUUID,
        Sluggable,
        SoftDeletes;

    protected $table = 'provinces';

    protected $fillable = ['title', 'code', 'uuid'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

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
