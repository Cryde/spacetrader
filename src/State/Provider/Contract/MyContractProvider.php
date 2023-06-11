<?php

namespace App\State\Provider\Contract;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\Facade\SpaceTraderFacade;

class MyContractProvider implements ProviderInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // accept :  --url 'https://api.spacetraders.io/v2/my/contracts/clir65jdr12w7s60ddtprbpoh/accept' \
        // TODO: Implement provide() method.
        return $this->spaceTraderFacade->getContracts();
    }
}