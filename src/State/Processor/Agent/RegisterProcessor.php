<?php

namespace App\State\Processor\Agent;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Agent\Register;
use App\Builder\User\TokenBuilder;
use App\Builder\User\UserBuilder;
use App\Builder\User\UserTokenBuilder;
use App\Contract\ErrorCode;
use App\Exception\Register\FailedTokenParseException;
use App\Exception\Register\TooOldTokenException;
use App\Exception\Register\UsernameNotAvailableException;
use App\Service\Facade\SpaceTraderFacade;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

readonly class RegisterProcessor implements ProcessorInterface
{
    public function __construct(
        private SpaceTraderFacade      $spaceTraderFacade,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserBuilder            $userBuilder,
        private UserTokenBuilder       $userTokenBuilder,
        private EntityManagerInterface $entityManager,
        private TokenBuilder $tokenBuilder
    ) {
    }

    /**
     * @param Register $data
     */
    public
    function process(
        mixed     $data,
        Operation $operation,
        array     $uriVariables = [],
        array     $context = []
    ) {
        // In this case user sent an username to register
        if ($data->username) {
            try {
                $spaceTraderApiToken = $this->spaceTraderFacade->register($data->username, $data->faction);

                $user = $this->userBuilder->build($data->username, $spaceTraderApiToken);
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $data->password));
                $userToken = $this->userTokenBuilder->build($user);

                $this->entityManager->persist($user);
                $this->entityManager->persist($userToken);
                $this->entityManager->flush($user);

                return $this->tokenBuilder->build($userToken);
            } catch (HttpExceptionInterface $e) {
                $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
                if ($errorData === null) {
                    throw $e;
                }
                if ($errorData['code'] === ErrorCode::SYMBOL_AGENT_ALREADY_TAKEN) {
                    throw new UsernameNotAvailableException('This username is already taken. Please choose another one.');
                }

                throw $e;
            }
        }
        if ($data->token) {
            try {
                // 1. we first try to call the agent api with the token user provide
                $agent = $this->spaceTraderFacade->getAgentWithAuthToken($data->token);

                $user = $this->userBuilder->build($agent->symbol, $data->token);
                $user->setPassword($this->userPasswordHasher->hashPassword($user, $data->password));
                $userToken = $this->userTokenBuilder->build($user);

                $this->entityManager->persist($user);
                $this->entityManager->persist($userToken);
                $this->entityManager->flush($user);

                return $this->tokenBuilder->build($userToken);
            } catch (ClientException $e) {
                $error = $e->getResponse()->toArray(false)['error'] ?? null;
                if ($error === null) {
                    throw $e;
                }

                if ($error['code'] === ErrorCode::FAILED_TO_PARSE_TOKEN) {
                    throw new FailedTokenParseException('Failed to parse this token. Please double check it');
                }
                if (str_contains($error['message'], 'Token reset_date does not match the server.')) {
                    throw new TooOldTokenException('Your token is out of date. Please register with an username');
                }
                throw $e;
            }
        }
    }
}