<?php

namespace App\State\Provider\System;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Facade\SpaceTraderFacade;

readonly class ShipyardProvider implements ProviderInterface
{
    public function __construct(public SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $systemSymbol = $uriVariables['systemSymbol'];
        $waypointSymbol = $uriVariables['waypointSymbol'];

        return $this->spaceTraderFacade->getShipyard($systemSymbol, $waypointSymbol);
    }
}