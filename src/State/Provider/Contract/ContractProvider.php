<?php

namespace App\State\Provider\Contract;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Facade\SpaceTraderFacade;

class ContractProvider implements ProviderInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $id = $uriVariables['id'];

        return $this->spaceTraderFacade->getContract($id);
    }
}