<?php

namespace App\State\Processor\Contract;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Contract\Contract;
use App\Service\Facade\SpaceTraderFacade;

readonly class ContractPostProcessor implements ProcessorInterface
{
    public function __construct(private SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param Contract $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->spaceTraderFacade->acceptContact($data->id);
    }
}