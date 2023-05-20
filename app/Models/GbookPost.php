<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GbookPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'gbook_posts';
    protected $guarded = [];

    public function getDateFormatted(): string
    {
        $post_date = Carbon::createFromTimestamp($this->date)->timezone("Asia/Vladivostok");
        $now = Carbon::now()->timestamp;
        if ($post_date >= Carbon::today())
            return 'Сегодня в ' . $post_date->format("H:i");
        if ($post_date >= Carbon::yesterday())
            return 'Вчера в ' . $post_date->format("H:i");
        return $post_date->format("d.m.Y H:i");
    }
    private function emotion($message)
    {
        $smile = array(":-)" => '<img src="/img/smiles/smilie1.gif" width="15" height="15">', ":-(" => '<img src="/img/smiles/smilie2.gif" width="15" height="15">', ";-)" => '<img src="/img/smiles/smilie3.gif" width="15" height="15">', ":D" => '<img src="/img/smiles/smilie5.gif" width="15" height="15">', ":-D" => '<img src="/img/smiles/smilie32.gif" width="15" height="15">', ":p" => '<img src="/img/smiles/smilie6.gif" width="15" height="15">', ":cool:" => '<img src="/img/smiles/smilie7.gif" width="21" height="15">', ":rolleyes:" => '<img src="/img/smiles/smilie8.gif" width="15" height="15">', ":E" => '<img src="/img/smiles/smilie9.gif" width="15" height="15">', ":eek:" => '<img src="/img/smiles/smilie10.gif" width="15" height="15">', ":confused:" => '<img src="/img/smiles/smilie11.gif" width="15" height="22">',
            ":vpered:" => '<img src="/img/smiles/vpered.gif" width="42" height="52">', ":]" => '<img src="/img/smiles/smilie13.gif" width="25" height="15">', ":?" => '<img src="/img/smiles/smilie14.gif" width="19" height="15">', ":hehe:" => '<img src="/img/smiles/smilie16.gif" width="16" height="16">', ":beer:" => '<img src="/img/smiles/smilie15.gif" width="57" height="16">', ":duel:" => '<img src="/img/smiles/smilie4.gif" width="82" height="19">', ":lol:" => '<img src="/img/smiles/smilie12.gif" width="15" height="15">', ":tick:" => '<img src="/img/smiles/smilie18.gif" width="15" height="15">', ":sorry:" => '<img src="/img/smiles/smilie17.gif" width="15" height="20">', ":-E" => '<img src="/img/smiles/smilie19.gif" width="15" height="15">', ":guru:" => '<img src="/img/smiles/smilie20.gif" width="27" height="22">', ":user:" => '<img src="/img/smiles/smilie21.gif" width="15" height="15">', ":glass:" => '<img src="/img/smiles/smilie22.gif" width="15" height="15">', ":sword:" => '<img src="/img/smiles/smilie23.gif" width="100" height="34">', ":alc:" => '<img src="/img/smiles/smilie24.gif" width="57" height="28">', ":yes:" => '<img src="/img/smiles/smilie25.gif" width="15" height="15">', ":no:" => '<img src="/img/smiles/smilie26.gif" width="15" height="15">', ":weep:" => '<img src="/img/smiles/smilie27.gif" width="21" height="15">', ":sleepy:" => '<img src="/img/smiles/smilie29.gif" width="27" height="15">', ":-|" => '<img src="/img/smiles/smilie30.gif" width="15" height="15">',
            ":respekt:" => '<img src="/img/smiles/respekt.gif" width="43" height="18">', ":football:" => '<img src="img/football.gif" width="15" height="30">',
            ":gogo:" => '<img src="/img/smiles/go-go.gif" width="98" height="31">',
            ":au:" => '<img src="/img/smiles/au.gif" width="29" height="27">',
            ":wow:" => '<img src="/img/smiles/wow.gif" width="19" height="19">',
            ":insane:" => '<img src="/img/smiles/insane.gif" width="15" height="15">',
            ":gigi:" => '<img src="/img/smiles/gigi.gif" width="15" height="15">',
            ":bye:" => '<img src="/img/smiles/smilie31.gif" width="15" height="15">',
            ":ole:" => '<img src="/img/smiles/ole.gif" width="229" height="38">', "8-E" => '<img src="/img/smiles/smilie28.gif" width="15" height="15">');
        for (reset($smile); $key = key($smile); next($smile)) {
            $message = str_replace("$key", "$smile[$key]", $message);
        }
        return $message;
    }

    public function prepareComment(): string
    {
        $comment = $this->comment;
        $comment = preg_replace("/\(#([0-9]+)\)/", " (<a href=\"#\\1\">#\\1</a>)", $comment);
        $comment = nl2br($comment);
        $comment = $this->emotion($comment);
        return $comment;
    }
}
