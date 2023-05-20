<?php

namespace App\Http\Resources\User;

use App\Models\GbookPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'thumb_url' => $this->image_url ? Storage::disk('public')->url('user_avatars/thumb_'.basename($this->image_url)) : null,
         ];
    }
}
