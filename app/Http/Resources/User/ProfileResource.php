<?php

namespace App\Http\Resources\User;

use App\Models\GbookPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use MoonShine\Models\MoonshineUser;
use MoonShine\Models\MoonshineUserRole;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    private function getRole($email): string
    {
        if ($user = MoonshineUser::where('email', $email)->first())
            return $user->moonshineUserRole->name;
        return 'User';
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nick' => $this->nick,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => !($this->email_verified_at === null),
            'city' => $this->city,
            'country' => $this->country,
            'favourite_clubs' => $this->favourite_clubs,
            'is_habar' => $this->is_habar,
            'post_count' => Cache::remember('postcount_' . $this->id, Carbon::now()->addDay(), fn() => GbookPost::where('user_id', $this->id)->count()),
            'image_url' => $this->image_url ? Storage::disk('public')->url($this->image_url) : null,
            'thumb_url' => $this->image_url ? Storage::disk('public')->url('user_avatars/thumb_' . basename($this->image_url)) : null,
            'banned_until' => $this->banned_until ? Carbon::make($this->banned_until)->format('d.m.Y') : null,
            'role' => $this->getRole($this->email)
        ];
    }
}
