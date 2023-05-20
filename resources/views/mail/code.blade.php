<x-mail::message>
    <b>Здравствуйте!</b><br><br>
    Ваш код активации учетной записи на сайте:
        <div style="font-size: 22px; background-color: #ecf2f6; padding: 20px 0; text-align: center; font-weight: bold">{{ $code }}</div>
    <br>Введите его на главной странице гостевой книги.<br><br>
    С уважением,<br>
    Администрация {{ config('app.name') }}
</x-mail::message>
