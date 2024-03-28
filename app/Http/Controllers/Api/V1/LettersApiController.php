<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Letter;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\Letter\LetterCollection;
use App\Http\Resources\Letter\LetterResource;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LettersApiController extends ApiBaseController
{
    public function index()
    {
        $index = Cache::rememberForever('index', function () {
            return Letter::published()->public()->get();
        });

        return new LetterCollection($index);
    }

    public function show(Letter $letter)
    {
        return new LetterResource($letter);
    }
}
