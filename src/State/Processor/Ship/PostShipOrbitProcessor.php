<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Ship\Ship;
use App\Service\Facade\SpaceTraderFacade;

class PostShipOrbitProcessor implements ProcessorInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param Ship $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->spaceTraderFacade->orbitShip($data->symbol);

        return null;
    }
}