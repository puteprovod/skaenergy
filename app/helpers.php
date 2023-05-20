<?php
function saltPassword(string $password, string $email){

    return $email.'RoHF'.md5($email.$password.'ZaJr');
}
