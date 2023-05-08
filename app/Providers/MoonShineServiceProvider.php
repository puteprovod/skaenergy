<?php

namespace App\Providers;

use App\MoonShine\Resources\Gbook\GbookPostResource;
use App\MoonShine\Resources\Gbook\PremodGbookPostResource;
use App\MoonShine\Resources\IpBanListResource;
use App\MoonShine\Resources\SettingResource;
use App\MoonShine\Resources\User\BannedUserResource;
use App\MoonShine\Resources\User\HabarUserResource;
use App\MoonShine\Resources\User\UserResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\MoonShine;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuGroup::make('Гостевая книга', [
                MenuItem::make('Все посты', new GbookPostResource())->icon('heroicons.outline.document-text'),
                 MenuItem::make('Премодерация', new PremodGbookPostResource())->icon('heroicons.outline.hand-raised'),
            ])->icon('heroicons.outline.book-open'),
            MenuGroup::make('Пользователи', [
                MenuItem::make('Все пользователи', new UserResource())->icon('heroicons.outline.users'),
                MenuItem::make('Члены клуба ХАБАР', new HabarUserResource())->icon('heroicons.star'),
                MenuItem::make('Находящиеся в бане', new BannedUserResource())->icon('heroicons.no-symbol'), //
            ])->icon('heroicons.outline.users'),
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('moonshine::ui.resource.admins_title', new MoonShineUserResource())
                    ->translatable()
                    ->icon('users')->canSee(function () {
                        return true;
                    }),
                MenuItem::make('moonshine::ui.resource.role_title', new MoonShineUserRoleResource())
                    ->translatable()
                    ->icon('bookmark'),
            ])->translatable()->icon('heroicons.at-symbol')->canSee(function($request) {
                $roleName = $request->user('moonshine')?->moonshineUserRole()->value('name');
                return $roleName == 'Admin';
            }),
            MenuItem::make('Настройки', new SettingResource())->icon('heroicons.outline.cog-8-tooth')
        ]);
    }
}
