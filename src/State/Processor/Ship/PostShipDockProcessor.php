<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Ship\Ship;
use App\Service\Facade\SpaceTraderFacade;

readonly class PostShipDockProcessor implements ProcessorInterface
{
    public function __construct(private SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param Ship $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->spaceTraderFacade->dockShip($data->symbol);

        return null;
    }
}