<?php

namespace App\Service\Client;

use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

#[AsDecorator(decorates: 'space.trader.client')]
class SpaceTraderHttpClientDecorator implements HttpClientInterface
{
    use DecoratorTrait;

    public function __construct(?HttpClientInterface $client = null, private readonly Security $security)
    {
        $this->client = $client ?? HttpClient::create();
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        if ($user = $this->security->getUser()) {
            /** @var User $user */
            $options['auth_bearer'] = $user->getSpaceTraderTokenApi();
        }

        return $this->client->request($method, $url, $options);
    }
}