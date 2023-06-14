<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Model\Ship\Sell;
use App\Service\Facade\SpaceTraderFacade;

class PostShipSellProcessor implements ProcessorInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param Sell $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->spaceTraderFacade->sell($data->shipSymbol, $data->unitSymbol, $data->amount);
    }
}