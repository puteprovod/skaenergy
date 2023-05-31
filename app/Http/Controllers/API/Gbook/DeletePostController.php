<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Models\GbookPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeletePostController extends Controller
{
    public function __invoke(GbookPost $post = null)
    {
        if ($post) {
            $post->delete();
            Cache::flush();
            return response()->json(['success' => 'success'], 200);
        }
        else{
            return response()->json(['message' => 'Пост не найден.'], 406);
        }
        // TODO: Implement __invoke() method.
    }
}
