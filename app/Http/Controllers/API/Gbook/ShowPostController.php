<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Gbook\IndexResource;
use App\Http\Resources\API\Gbook\PostResource;
use App\Models\GbookPost;
use Illuminate\Http\Request;

class ShowPostController extends Controller
{
    public function __invoke(GbookPost $post)
    {
        return PostResource::make($post);
    }
}
