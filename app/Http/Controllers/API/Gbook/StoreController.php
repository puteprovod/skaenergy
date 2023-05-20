<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Gbook\StoreRequest;
use App\Http\Resources\API\Gbook\PostResource;
use App\Http\Resources\API\Gbook\IndexResource;
use App\Models\GbookPost;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $settings = App::make(Setting::class);
        $data = $request->validated();
        $user = $request->user();
        if (GbookPost::where('comment', $data['comment'])->where('date', '>=', Carbon::now()->subMinutes(30)->timestamp)->count() > 0)
            return response()->json(['message' => 'Повторяющийся комментарий.'], 406);
        if (GbookPost::where('user_id', $user->id)->where('date', '>=', Carbon::now()->subHours(6)->timestamp)->count() > 4)
            return response()->json(['message' => 'Флуд. Слишком много сообщений от одного пользователя.'], 406);

        $user = $request->user();
        $post = GbookPost::create([
            'name' => $user['nick'],
            'city' => $user['city'],
            'date' => Carbon::now()->timestamp,
            'host' => $request->getHttpHost(),
            'comment' => $this->AGCodez($data['comment']),
            'user_id' => $user->id,
            'premod' => !$settings->gbook->moderation_mode,
        ]);
        Cache::flush();
        return PostResource::make($post);
    }

    public function AGCodez($string)
    {
        $string = preg_replace("/\[img\]([^\[]*)\[\/img\]/", "<img src=\"\\1\" border=0>", $string);
        $string = preg_replace("/\[b\]([^\[]*)\[\/b\]/", "<b>\\1</b>", $string);
        $string = preg_replace("/\[i\]([^\[]*)\[\/i\]/", "<i>\\1</i>", $string);
        $string = preg_replace("/\[u\]([^\[]*)\[\/u\]/", "<u>\\1</u>", $string);
        $string = preg_replace("/\[email\]([^\[]*)\[\/email\]/", "<a href=\"mailto:\\1\">\\1</a>", $string);
        $string = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" >$3</a>", $string);
        $string = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" >$3</a>", $string);
       return $string;
    }
}
