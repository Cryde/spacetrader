<?php

namespace App\Security;

use Random\Randomizer;

class TokenGenerator
{
    public function generate(): string
    {
        return hash('sha512', bin2hex((new Randomizer())->getBytes(34)));
    }
}