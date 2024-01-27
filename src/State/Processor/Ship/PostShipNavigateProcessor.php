<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Ship\BuyShip;
use App\ApiResource\Ship\Ship;
use App\ApiResource\Ship\ShipNavigate;
use App\Service\Facade\SpaceTraderFacade;

readonly class PostShipNavigateProcessor implements ProcessorInterface
{
    public function __construct(private SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param ShipNavigate $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->spaceTraderFacade->navigate($data->shipSymbol, $data->waypointSymbol);
    }
}