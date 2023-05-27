<?php

namespace App\Http\Controllers\API\Gbook;

use App\Http\Controllers\Controller;
use App\Mail\Gbook\ReportMail;
use App\Mail\User\CodeMail;
use App\Models\GbookPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function __invoke(Request $request, GbookPost $post)
    {
        $errorMessage = '';
        $user = $request->user();
        try {
            Mail::to('serg-419@yandex.ru')->send(new ReportMail($post, $user));
            Mail::to('chernovss@mail.ru')->send(new ReportMail($post, $user));
        } catch (\Exception $exception2) {
            $errorMessage = "Ошибка отправки почты " . $exception2;
        }
        if ($errorMessage) {
            return response()->json(['message' => $errorMessage], 406);
        } else {
            return response()->json(['success' => 'success'], 200);
        }
    }
}
