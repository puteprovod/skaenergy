<?php
include "../vendor/autoload.php";
// Показывать всю информацию, по умолчанию INFO_ALL
dump(\Carbon\Carbon::today()->toDateTimeString());
dump(\Carbon\Carbon::yesterday()->toDateTimeString());
phpinfo();
