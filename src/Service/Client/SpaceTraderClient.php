<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpaceTraderClient
{
    final const GET_MY_AGENT = '/v2/my/agent';

    public function __construct(private readonly HttpClientInterface $spaceTraderClient)
    {
    }

    public function getAgent(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_AGENT);
    }
}