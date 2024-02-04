<?php

namespace App\Builder\User;

use App\ApiResource\Agent\Token;
use App\Entity\User\UserToken;

class TokenBuilder
{
    public function build(UserToken $userToken): Token
    {
        $token = new Token();
        $token->token = $userToken->getToken();

        return $token;
    }
}