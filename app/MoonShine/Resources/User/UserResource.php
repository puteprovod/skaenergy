<?php

namespace App\MoonShine\Resources\User;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use MoonShine\Actions\FiltersAction;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Button;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Image;
use MoonShine\Fields\NoInput;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;
use MoonShine\FormActions\FormAction;
use MoonShine\ItemActions\ItemAction;
use MoonShine\Resources\Resource;

class UserResource extends Resource
{
    public static string $model = User::class;

    public static string $title = 'Пользователи';

    public string $titleField = 'name';
    public static string $subTitle = 'Просмотр и изменение информации о пользователях';

    public static string $orderField = 'created_at';

    protected bool $editInModal = true;

    public static string $orderType = 'DESC';
    public static array $activeActions = ['edit', 'delete'];

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make('Информация о пользователе', [
                        NoInput::make('ID', 'id', fn($item) => '#' . $item->id)->badge('green')->sortable(),
                        Text::make('Никнейм', 'nick')->readonly()->sortable(),
                        Image::make('Аватар','image_url')->disk('public')
                            ->dir('user_avatars')->allowedExtensions(['png','gif','jpg','svg','webp'])->removable()->hideOnIndex(),
                        Email::make('E-mail', 'email')->hideOnIndex()->readonly(),
                        Text::make('Имя', 'name')->readonly()->hideOnIndex(),
                        Text::make('Город', 'city')->readonly(),
                        Date::make('Дата рождения', 'birth_date')->format('d.m.Y')->readonly()->hideOnIndex(),
                    ])
                ])->columnSpan(6),
                Column::make([
                    Block::make('Данные регистрации', [
                        Date::make('Дата регистрации', 'created_at')->format('d.m.Y')->readonly()->sortable(),
                        Date::make('Дата верификации E-mail', 'email_verified_at')->format('d.m.Y')->hideOnIndex()->readonly(),
                    ]),
                    Block::make('Измененяемые параметры', [
                        SwitchBoolean::make('Член клуба ХАБАР', 'is_habar')->sortable()->hideOnIndex(),
                        Date::make('В бане до:', 'banned_until',
                            function ($item) { return ($item->banned_until === null) ? '' : $item->banned_until; })
                            ->format('d.m.Y')->hideOnIndex(),
                        NoInput::make('Бан-лист', 'banned_until',
                            fn($item) => ($item->banned_until > '0000-00-00') ? 'бан до ' . Carbon::parse($item->banned_until)->format('d.m.Y') : 'чист')
                            ->badge(fn($item) => ($item->banned_until > '0000-00-00') ? 'red' : 'green')->sortable()->hideOnForm(),
                    ])
                ])->columnSpan(6)
            ]),


        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }

    public function search(): array
    {
        return ['id', 'nick', 'name'];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }

    public function itemActions(): array
    {
        $now = Carbon::now();
        return [
            ItemAction::make('Бан на неделю', function (User $item) use ($now) {
                $item->banned_until = $now->addDays(7)->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на неделю')->icon('heroicons.speaker-x-mark'),
            ItemAction::make('Бан на месяц', function (Model $item) use ($now) {
                $item->banned_until = $now->addMonth()->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на месяц')->icon('heroicons.outline.face-frown'),
            ItemAction::make('Бан на год', function (Model $item) use ($now) {
                $item->banned_until = $now->addYear()->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на год')->icon('heroicons.no-symbol'),
            ItemAction::make('Удалить из бан-листа', function (Model $item) {
                $item->banned_until = null;
                $item->save();
            }, 'Пользователь успешно разбанен')->icon('heroicons.outline.face-smile')->canSee(fn($item) => $item->banned_until > $now->toDateString()),

        ];
    }

    public function formActions(): array
    {
        $now = Carbon::now();
        return [
            FormAction::make('Бан на неделю', function (User $item) use ($now) {
                $item->banned_until = Carbon::now()->addDays(7)->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на неделю')->icon('heroicons.speaker-x-mark')->showInLine(),
            FormAction::make('Бан на месяц', function (Model $item) use ($now) {
                $item->banned_until = Carbon::now()->addMonth()->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на месяц')->icon('heroicons.outline.face-frown')->showInLine(),
            FormAction::make('Бан на год', function (Model $item) use ($now) {
                $item->banned_until = Carbon::now()->addYear()->toDateString();
                $item->save();
            }, 'Пользователь успешно забанен на год')->icon('heroicons.no-symbol')->showInLine(),
            FormAction::make('Удалить из бан-листа', function (Model $item) {
                $item->banned_until = null;
                $item->save();
            }, 'Пользователь успешно разбанен')->icon('heroicons.outline.face-smile')->showInLine()->canSee(fn($item) => $item->banned_until > $now->toDateString()),
        ];
    }
    protected function afterUpdated(Model $item)
    {
        if (isset($item->image_url)){
            \Intervention\Image\Facades\Image::make(storage_path('app/public/user_avatars/'.basename($item->image_url)))->fit(500,500)->save(storage_path('app/public/user_avatars/'.basename($item->image_url)),95);
            \Intervention\Image\Facades\Image::make(storage_path('app/public/user_avatars/'.basename($item->image_url)))->fit(100,100)->save(storage_path('app/public/user_avatars/thumb_'.basename($item->image_url)),95);
        }
        Cache::flush();
    }
}
