<?php

namespace App\Service\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpaceTraderClient
{
    final const GET_MY_AGENT = '/v2/my/agent';
    final const GET_MY_CONTRACT_BY_ID = '/v2/my/contracts/';
    final const POST_ACCEPT_CONTRACT_BY_ID = '/v2/my/contracts/%s/accept';
    final const POST_FULFILL_CONTRACT_BY_ID = '/v2/my/contracts/%s/fulfill';
    final const GET_MY_CONTRACTS = '/v2/my/contracts/';
    final const GET_MY_SHIPS = '/v2/my/ships/';
    final const GET_WAYPOINTS_BY_SYSTEM = '/v2/systems/%s/waypoints';
    final const GET_SHIPYARD_BY_SYSTEM_AND_WAYPOINT = '/v2/systems/%s/waypoints/%s/shipyard';
    final const GET_MARKET_BY_SYSTEM_AND_WAYPOINT = '/v2/systems/%s/waypoints/%s/market';

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

    public function acceptContract(string $id): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', sprintf(self::POST_ACCEPT_CONTRACT_BY_ID, $id));
    }

    public function fulfillContract(string $id): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', sprintf(self::POST_FULFILL_CONTRACT_BY_ID, $id));
    }

    public function getContracts(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_CONTRACTS);
    }

    public function getMyShips(): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_SHIPS);
    }

    public function getMyShip(string $symbol): ResponseInterface
    {
        return $this->spaceTraderClient->request('GET', self::GET_MY_SHIPS . $symbol);
    }

    public function getWaypointsBySystemSymbol(
        string $systemSymbol,
        int $page,
        ?string $trait = null,
        ?string $type = null
    ): ResponseInterface {
        return $this->spaceTraderClient->request('GET', sprintf(self::GET_WAYPOINTS_BY_SYSTEM, $systemSymbol), [
            'query' => [
                'page' => $page,
                'traits' => $trait,
                'type' => $type,
            ],
        ]);
    }

    public function getShipyard(string $systemSymbol, string $waypointSymbol): ResponseInterface {
        return $this->spaceTraderClient->request(
            'GET',
            sprintf(self::GET_SHIPYARD_BY_SYSTEM_AND_WAYPOINT, $systemSymbol, $waypointSymbol)
        );
    }

    public function getMarket(string $systemSymbol, string $waypointSymbol): ResponseInterface {
        return $this->spaceTraderClient->request(
            'GET',
            sprintf(self::GET_MARKET_BY_SYSTEM_AND_WAYPOINT, $systemSymbol, $waypointSymbol)
        );
    }

    public function navigate(string $shipSymbol, string $waypointSymbol): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $shipSymbol . '/navigate', [
            'json' => [
                'waypointSymbol' => $waypointSymbol,
            ],
        ]);
    }

    public function buyShip(string $shipType, string $waypointSymbol): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS, [
            'json' => [
                'shipType' => $shipType,
                'waypointSymbol' => $waypointSymbol
            ]
        ]);
    }

    public function dockShip(string $identifier): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $identifier . '/dock');
    }

    public function orbitShip(string $identifier): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $identifier . '/orbit');
    }

    public function jettison(string $identifier, string $inventorySymbol, int $quantity): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $identifier . '/jettison', [
            'json' => [
                "symbol" => $inventorySymbol,
                "units"  => $quantity,
            ],
        ]);
    }

    public function sell(string $identifier, string $inventorySymbol, int $quantity): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $identifier . '/sell', [
            'json' => [
                "symbol" => $inventorySymbol,
                "units"  => $quantity,
            ],
        ]);
    }

    public function deliverContract(
        string $shipSymbol,
        string $contractId,
        string $tradeSymbol,
        int    $units
    ): ResponseInterface {
        return $this->spaceTraderClient->request('POST', self::GET_MY_CONTRACTS . $contractId . '/deliver', [
            'json' => [
                "shipSymbol"  => $shipSymbol,
                "tradeSymbol" => $tradeSymbol,
                "units"       => $units,
            ],
        ]);
    }

    public function extract(string $identifier): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $identifier . '/extract');
    }


    public function refuel(string $shipSymbol, int $units): ResponseInterface
    {
        return $this->spaceTraderClient->request('POST', self::GET_MY_SHIPS . $shipSymbol . '/refuel', [
            'json' => [
                'units' => $units,
            ],
        ]);
    }
}