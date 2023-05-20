<?php

namespace App\Models;

use App\Mail\User\CodeMail;
use App\Mail\User\PasswordResetMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nick',
        'email',
        'password',
        'city',
        'country',
        'favourite_clubs',
        'email_verified_at',
        'image_url'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function makeNewVerificationCode(): int
    {
        $randomInt = random_int(100000, 999999);
        Cache::put($this->email, $randomInt, Carbon::now()->addDays(30));
        return $randomInt;
    }

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new PasswordResetMail($token, $this->email));
    }
    protected static function boot(): void
    {
        parent::boot();

        static::updated(static function (User $item) {
            if ($item->wasChanged('image_url') && $item->getOriginal('image_url')) {
                Storage::disk('public')->delete($item->getOriginal('image_url'));
                Storage::disk('public')->delete('user_avatars/thumb_'.basename($item->getOriginal('image_url')));
            }
        });
    }
}
