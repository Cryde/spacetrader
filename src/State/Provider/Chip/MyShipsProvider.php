<?php

namespace App\State\Provider\Chip;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Facade\SpaceTraderFacade;

class MyShipsProvider implements ProviderInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        return $this->spaceTraderFacade->getMyShips();
    }
}