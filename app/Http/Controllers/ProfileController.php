<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        if (isset($data['avatar'])) {
            $data['image_url'] = Storage::disk('public')->put('/user_avatars', $data['avatar']);
            Image::make($data['avatar'])->fit(500,500)->save(public_path('storage/user_avatars/'.basename($data['image_url'])));
           Image::make($data['avatar'])->fit(100,100)->save(public_path('storage/user_avatars/thumb_'.basename($data['image_url'])));
        }
        $request->user()->fill($data);

//        if ($request->user()->isDirty('email')) {
//            $request->user()->email_verified_at = null;
//        }

        $request->user()->save();
        Cache::flush();
        return response()->json(['success' => 'success'], 200);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required'],
        ]);
        $user = $request->user();
        $editedPasswordHash = Hash::make(saltPassword($validated['password'],$user->email));
        if ($user->password === $editedPasswordHash) {
            Auth::logout();
            $user->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['success' => 'success'], 200);
        }
        return response()->json(['message' => 'Неверно указан текущий пароль'], 406);
    }

    public function destroyImage(Request $request)
    {
        $user = $request->user();
        Storage::disk('public')->delete('/user_avatars', $user->image_url);
        $user->image_url = null;
        $user->save();
        Cache::flush();
        return response()->json(['success' => 'success'], 200);
    }
}
