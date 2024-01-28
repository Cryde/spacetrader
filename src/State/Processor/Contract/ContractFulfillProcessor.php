<?php

namespace App\State\Processor\Contract;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Contract\Fulfill\ContractFulfill;
use App\Service\Facade\SpaceTraderFacade;

readonly class ContractFulfillProcessor implements ProcessorInterface
{
    public function __construct(private SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param ContractFulfill $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->spaceTraderFacade->fulfillContract($data->contractId);
    }
}