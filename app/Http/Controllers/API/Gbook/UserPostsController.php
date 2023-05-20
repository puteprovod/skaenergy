<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\Gbook\IndexResource;
use App\Models\GbookPost;
use App\Models\User;
use Illuminate\Http\Request;

class UserPostsController extends Controller
{

    public function __invoke(User $user)
    {
        $posts = GbookPost::where('user_id',$user->id)->get();
        return IndexResource::collection($posts);

    }
}
