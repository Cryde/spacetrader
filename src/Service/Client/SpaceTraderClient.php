<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpaceTraderClient
{
    final const GET_MY_AGENT = '/v2/my/agent';
    final const GET_MY_CONTRACT_BY_ID = '/v2/my/contracts/';
    final const GET_MY_CONTRACTS = '/v2/my/contracts/';
    final const GET_MY_SHIPS = '/v2/my/ships/';

    public function __construct(private readonly HttpClientInterface $spaceTraderClient)
    {
    }

    public function getAgent(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_AGENT);
    }

    public function getContract(string $id): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_CONTRACT_BY_ID . $id);
    }

    public function getContracts(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_CONTRACTS);
    }

    public function getMyShips(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_SHIPS);
    }
}