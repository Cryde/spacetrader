<?php

namespace App\Builder\User;

use App\Entity\User\User;
use App\Entity\User\UserToken;
use App\Security\TokenGenerator;

readonly class UserTokenBuilder
{
    public function __construct(private TokenGenerator $tokenGenerator)
    {
    }

    public function build(User $user): UserToken
    {
        return (new UserToken())
            ->setUserReference($user)
            ->setToken($this->tokenGenerator->generate());
    }
}