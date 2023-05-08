<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;

class SkaenergyHasher extends AbstractHasher implements Hasher
{

    public function make($value, array $options = [])
    {
        return md5($value);
    }

    public function check($value, $hashedValue, array $options = []){
        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
