<x-mail::message>
    <b>Репорт</b><br><br>
    <b>{{ $post->getDateFormatted() }} посетитель
        @if($postUser)
        <a href="{{ asset('admin/resource/user-resource/'.$postUser->id.'/edit') }}">{{ $postUser->nick }}</a>
        @else
        {{ $post->name }}
        @endif
        написал:</b><br><hr>
        "{{ $post->comment }}"
    <hr>
    <br>
    <a href="{{ asset('admin/resource/gbook-post-resource/'.$post->id.'/edit') }}">Перейти к управлению данным постом</a><br><br>
    Сообщил о нарушении правил: <a href="{{ asset('admin/resource/user-resource/'.$reportedUser->id.'/edit') }}">{{ $reportedUser->nick }}</a>

</x-mail::message>
