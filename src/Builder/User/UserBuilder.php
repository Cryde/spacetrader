<?php

namespace App\Builder\User;

use App\Entity\User\User;

class UserBuilder
{
    public function build(string $username, string $spaceTraderTokenApi): User
    {
        return (new User())
            ->setUsername($username)
            ->setSpaceTraderTokenApi($spaceTraderTokenApi);
    }
}