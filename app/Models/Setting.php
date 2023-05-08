<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $guarded = [];
    protected $casts = [
        'blocked_ips' => 'collection'
    ];

    public static function getSettings()
    {
        return Cache::rememberForever('settings', function () {
            return json_decode(self::find(1)->settings);
        });
    }
}
