<?php

namespace App\MoonShine\Resources\Gbook;

use App\Models\GbookPost;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MoonShine\Actions\FiltersAction;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Button;
use MoonShine\Fields\ID;
use MoonShine\Fields\NoInput;
use MoonShine\Fields\Text;
use MoonShine\Fields\Textarea;
use MoonShine\Filters\TextFilter;
use MoonShine\ItemActions\ItemAction;
use MoonShine\Resources\Resource;

class GbookPostResource extends Resource
{
    public static string $model = GbookPost::class;

    public static string $title = 'Гостевая книга';

    public static string $subTitle = 'Просмотр и изменение постов';

    public static array $activeActions = ['edit', 'delete'];

    public static bool $simplePaginate = true;

    public function fields(): array
    {
        return [
            Block::make('Редактирование поста', [
                ID::make()->sortable(),
                NoInput::make('Дата и время', 'date', function ($item) {
                    return $item->getDateFormatted();
                })->readonly(),
                NoInput::make('Посетитель', 'name', function ($item1) {
                    if ($item1->user_id > 0) {
                        $route = (new \App\MoonShine\Resources\User\UserResource)->route('edit', $item1->user_id);
                        $userString = '&#128100;&nbsp;&nbsp;<a href="'.$route.'">' . $item1->name . '</a>';
                        if ($user = User::find($item1->user_id))
                            if ($user->banned_until > Carbon::now()->toDateString())
                                $userString = '&#128683;&nbsp;&nbsp;<a href="'.$route.'">' . $item1->name . '</a>';
                    } else {
                        $userString = '&#129399;&nbsp;&nbsp;' . $item1->name;
                    }
                    return '<div class="font-medium">' . $userString . '</div>';
                })->readonly(),
                Text::make('Город', 'city')->hideOnIndex()->readonly(),
                Textarea::make('Текст поста', 'comment')->customAttributes(['rows' => 15])->required()->hideOnIndex(),
                Text::make('Текст поста', 'comment', function ($item) {
                    $comment = $item->comment;
                    if (strlen($comment) > 800)
                        $comment = substr($comment, 0, 500) . '...';
                    return $comment;
                })->readonly()->hideOnForm()->hideOnDetail(),
                Text::make('IP пользователя', 'host')->readonly()->hideOnIndex(),
                Button::make('Удалить пост и забанить пользователя на месяц',$this->getItem() ? $this->route('actions.item', $this->getItem()->getKey(),
                    ['index' => $this->getItem()->user_id ? '1' : '0']) : '/',true)->icon('heroicons.no-symbol')
            ])
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['id', 'name', 'comment'];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('Все посты от пользователя', 'name'),
        ];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }

    public function itemActions(): array
    {
        return [
            ItemAction::make('Удалить и бан IP на месяц', function ($item) {
                $setting = Setting::find(1);
                $setting->blocked_ips = $setting->blocked_ips->push([
                    'ip' => $item->host,
                    'finish_date' => Carbon::now()->addMonth()->toDateString()
                ]);
                $setting->save();
                $item->delete();
            }, 'Пользователь успешно заблокирован по IP')->canSee(fn($item) => $item->user_id == 0)
                ->icon('heroicons.no-symbol')->withConfirm(),
            ItemAction::make('Удалить и бан пользователя на месяц', function ($item) {
                if ($user = User::find($item->user_id)) {
                    $user->banned_until = Carbon::now()->addMonth()->toDateString();
                    $user->save();
                }
                $item->delete();
            }, 'Пользователь успешно заблокирован')->canSee(fn($item) => $item->user_id > 0)
                ->withConfirm()->icon('heroicons.no-symbol'),
        ];
    }
    protected function afterUpdated(Model $item)
    {
        Cache::flush();
    }
    protected function afterDeleted(Model $item)
    {
        Cache::flush();
    }
    protected function afterMassDeleted(array $ids)
    {
        Cache::flush();
    }
}
