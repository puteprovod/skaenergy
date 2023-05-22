<?php

namespace App\MoonShine;

use App\Models\GbookPost;
use App\Models\Setting;
use App\Models\User;
use App\MoonShine\Resources\Gbook\GbookPostResource;
use App\MoonShine\Resources\User\UserResource;
use Carbon\Carbon;
use Dotenv\Parser\Value;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use MoonShine\Dashboard\DashboardBlock;
use MoonShine\Dashboard\DashboardScreen;
use MoonShine\Dashboard\ResourcePreview;
use MoonShine\Dashboard\TextBlock;
use MoonShine\Metrics\LineChartMetric;
use MoonShine\Metrics\ValueMetric;

class Dashboard extends DashboardScreen
{
    public function __construct()
    {
        Cache::remember('banlist_cleared', Carbon::now()->addDay(1), function () {
            User::where('banned_until', '<', Carbon::now()->toDateString())->update(['banned_until' => null]);
            return true;
        });
    }

    public function blocks(): array
    {
        $settings = App::make(Setting::class);
        $string = 'Режим модерации ';
        $string.= $settings->gbook->moderation_mode ? 'включен' : 'отключен';
        $string.= '.';
        return [
            DashboardBlock::make([
                TextBlock::make('',$string),
                ValueMetric::make('Постов за сутки')
                    ->value(GbookPost::where('date', '>', Carbon::now()->subDay()->timestamp)->count())->columnSpan(3),
                ValueMetric::make('Постов за месяц')
                    ->value(GbookPost::where('date', '>', Carbon::now()->subMonth()->timestamp)->count())->columnSpan(3),
                ValueMetric::make('Новых пользователей за месяц')
                    ->value(User::where('created_at', '>', Carbon::now()->subMonth()->toDateTimeString())->count())->columnSpan(3),
                ValueMetric::make('Пользователей в бане')
                    ->value(User::where('banned_until', '<>', '0000-00-00')->count())->columnSpan(3),
                LineChartMetric::make('Посты')
                    ->line([
                        'Posts' => GbookPost::query()
                            ->selectRaw('COUNT(id) as sum, FROM_UNIXTIME(date,"%Y-%m-%d") as datex')->where('date','>',Carbon::now()->subMonths(1)->timestamp)
                            ->groupBy('datex')
                            ->pluck('sum', 'datex')->sortBy('datex')
                            ->toArray()
                    ])->columnSpan(12),
                ResourcePreview::make(
                    new GbookPostResource(), // Обязательный параметр с ресурсом MoonShine
                    'Последние сообщения в гостевой книге', // Опционально - заголовок блока
                    GbookPost::query()->orderBy('date', 'DESC')->limit(10) // Опционально QueryBuilder
                ),
                ResourcePreview::make(
                    new UserResource(), // Обязательный параметр с ресурсом MoonShine
                    'Последние зарегистрированные пользователи', // Опционально - заголовок блока
                    User::query()->orderBy('created_at', 'DESC')->limit(10) // Опционально QueryBuilder
                )
            ])

        ];
    }

}
