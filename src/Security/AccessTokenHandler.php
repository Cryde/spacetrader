<?php

namespace App\Security;

use App\Repository\User\UserTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserTokenRepository $userTokenRepository
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $userToken = $this->userTokenRepository->findOneBy(['token' => $accessToken]);
        if ($userToken === null) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        // and return a UserBadge object containing the user identifier from the found token
        return new UserBadge($userToken->getUserReference()->getUserIdentifier());
    }
}