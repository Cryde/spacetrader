<?php

namespace App\ApiResource\System;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\Provider\System\WaypointCollectionProvider;

#[GetCollection(
    uriTemplate: 'systems/{systemSymbol}/waypoints',
    name: 'api_waypoints_collection',
    provider: WaypointCollectionProvider::class
)]
class Waypoint
{
    public string $systemSymbol;
    public string $symbol;
    public ?string $orbits = null;
    public string $type;
    public bool $isUnderConstruction;
    public int $x;
    public int $y;
    public array $orbitals = []; // todo
    /** @var WTrait[] */
    public array $traits;
    public array $modifiers;
    public Chart $chart;
    public Faction $faction;
}
