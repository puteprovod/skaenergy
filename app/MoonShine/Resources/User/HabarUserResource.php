<?php

namespace App\MoonShine\Resources\User;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MoonShine\Actions\FiltersAction;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Grid;
use MoonShine\Fields\Date;
use MoonShine\Fields\Email;
use MoonShine\Fields\Image;
use MoonShine\Fields\NoInput;
use MoonShine\Fields\SwitchBoolean;
use MoonShine\Fields\Text;

class HabarUserResource extends UserResource
{
    public static int $itemsPerPage = 100;
	public static string $title = 'Члены клуба ХАБАР';
    public static string $subTitle = 'Просмотр списка членов клуба';
    public static array $activeActions = ['show'];

    public function query(): Builder
    {
        return parent::query()->where('is_habar','=',1);
    }

    public function fields(): array
    {
        return [
            Grid::make([
                Column::make([
                    Block::make('Информация о пользователе', [
                        NoInput::make('ID', 'id', fn($item) => '#' . $item->id)->badge('green')->sortable(),
                        Image::make('','',fn() => '/img/habar.png'),
                        Text::make('Никнейм', 'nick')->readonly()->sortable(),
                        Email::make('E-mail', 'email')->readonly(),
                        Text::make('Имя', 'name')->readonly()->hideOnIndex(),
                        Text::make('Город', 'city')->readonly(),
                        Date::make('Дата рождения', 'birth_date')->format('d.m.Y')->readonly()->hideOnIndex(),
                    ])
                ])->columnSpan(6),
                Column::make([
                    Block::make('Данные регистрации', [
                        Date::make('E-mail верфицирован', 'email_verified_at')->format('d.m.Y')->hideOnIndex()->readonly(),
                        Date::make('Зарегистрирован', 'created_at')->format('d.m.Y')->readonly()->hideOnIndex(),
                    ]),
                    Block::make('Инструменты', [
                        SwitchBoolean::make('Член клуба ХАБАР', 'is_habar'),
                        Date::make('В бане до:', 'banned_until',
                            fn($item) => ($item->banned_until <> '0000-00-00') ? $item->banned_until : '')
                            ->format('d.m.Y')->hideOnIndex(),
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
        return ['id'];
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
}
