<?php

namespace App\ApiResource\System;

use ApiPlatform\Metadata\GetCollection;
use App\ApiResource\System\Shipyard\Ship;
use App\ApiResource\System\Shipyard\ShipType;
use App\ApiResource\System\Shipyard\Transaction;
use App\State\Provider\System\ShipyardProvider;

#[GetCollection(
    uriTemplate: 'systems/{systemSymbol}/waypoints/{waypointSymbol}/shipyard',
    name: 'api_shipyard_get',
    provider: ShipyardProvider::class
)]
class Shipyard
{
    public string $symbol;
    /** @var ShipType[] */
    public array $shipTypes;
    /** @var Transaction[] */
    public array $transactions;
    /** @var Ship[] */
    public array $ships;
    public int $modificationsFee;
}