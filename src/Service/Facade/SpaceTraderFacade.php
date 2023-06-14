<?php

namespace App\Service\Facade;

use App\Model\Agent;
use App\Model\Contract\Contract;
use App\Model\Ship\Ship;
use App\Service\Client\SpaceTraderClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SpaceTraderFacade
{
    public function __construct(
        private readonly SpaceTraderClient     $spaceTraderClient,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function getAgent(): Agent
    {
        $response = $this->spaceTraderClient->getAgent();

        return $this->denormalizer->denormalize($response->toArray()['data'], Agent::class);
    }

    public function getContract(string $id): Contract
    {
        $response = $this->spaceTraderClient->getContract($id);

        return $this->denormalizer->denormalize($response->toArray()['data'], Contract::class);
    }

    public function getContracts()
    {
        $response = $this->spaceTraderClient->getContracts();

        return $this->denormalizer->denormalize($response->toArray()['data'], Contract::class . '[]');
    }

    public function getMyShips()
    {
        $response = $this->spaceTraderClient->getMyShips();

        return $this->denormalizer->denormalize($response->toArray()['data'], Ship::class . '[]');
    }

    public function dockShip(string $identifier): void
    {
        $this->spaceTraderClient->dockShip($identifier);
    }

    public function orbitShip(string $identifier): void
    {
        $this->spaceTraderClient->orbitShip($identifier);
    }

    public function sell(string $identifier, string $inventorySymbol, int $quantity): void
    {
        $this->spaceTraderClient->sell($identifier, $inventorySymbol, $quantity);
    }
}