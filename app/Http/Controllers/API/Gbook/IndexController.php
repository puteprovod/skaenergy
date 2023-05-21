<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Gbook\IndexRequest;
use App\Http\Resources\API\Gbook\IndexResource;
use App\Models\GbookPost;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request, User $user = null)
    {
        $settings = App::make(Setting::class);
        $data = $request->validated();
        $numberOfPosts = isset($data['last']) ? $settings->gbook->index_posts_additional : $settings->gbook->index_posts_count;
        $whereArray = [];
        $whereArray[] = ['premod', true];
        $postfix = '';

        if (isset($data['last'])) {
            $whereArray[] = ['gbook_posts.id', '<', $data['last']];
            $postfix = '_'.$data['last'];
        }
        if (!$user) {
            return Cache::remember('gbook_posts' . $postfix, Carbon::now()->addHour(), function () use ($whereArray, $numberOfPosts) {
                $posts = GbookPost::leftJoin('users', 'gbook_posts.user_id', '=', 'users.id')->select('gbook_posts.*', 'users.image_url as image_url')
                    ->where($whereArray)->orderBy('date', 'DESC')->limit($numberOfPosts)->get();
                return IndexResource::collection($posts);
            });
        } else {
            $whereArray[] = ['user_id', $user->id];
            $posts = GbookPost::leftJoin('users', 'gbook_posts.user_id', '=', 'users.id')->select('gbook_posts.*', 'users.image_url as image_url')
                ->where($whereArray)->orderBy('date', 'DESC')->limit($numberOfPosts)->get();
            return IndexResource::collection($posts); //
        }
    }
}
