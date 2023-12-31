<?php

namespace App\Service\Facade;

use App\Model\Agent;
use App\Model\Contract\Contract;
use App\Model\Error;
use App\Model\Extract\Extract;
use App\Model\Navigation\Navigation;
use App\Model\Ship\Ship;
use App\Service\Cache\CacheFactory;
use App\Service\Client\SpaceTraderClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SpaceTraderFacade
{
    public function __construct(
        private readonly SpaceTraderClient     $spaceTraderClient,
        private readonly DenormalizerInterface $denormalizer,
        private readonly CacheFactory          $cacheFactory
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

    /**
     * @return Contract[]
     */
    public function getContracts(): array
    {
        $cache = $this->cacheFactory->create();

        return $cache->get('my_contracts', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag([CacheFactory::TAG_MY_CONTRACTS]);
            $response = $this->spaceTraderClient->getContracts();

            return $this->denormalizer->denormalize($response->toArray()['data'], Contract::class . '[]');
        });
    }

    public function getMyShips()
    {
        $response = $this->spaceTraderClient->getMyShips();

        return $this->denormalizer->denormalize($response->toArray()['data'], Ship::class . '[]');
    }

    public function navigate(string $shipSymbol, string $waypointSymbol): Navigation
    {
        $response = $this->spaceTraderClient->navigate($shipSymbol, $waypointSymbol);

        return $this->denormalizer->denormalize($response->toArray()['data'], Navigation::class);
    }

    public function getShip(string $identifier): Ship
    {
        $response = $this->spaceTraderClient->getMyShip($identifier);

        return $this->denormalizer->denormalize($response->toArray()['data'], Ship::class);
    }

    public function dockShip(string $identifier): void
    {
        $this->spaceTraderClient->dockShip($identifier);
    }

    public function orbitShip(string $identifier): void
    {
        $this->spaceTraderClient->orbitShip($identifier);
    }

    public function extract(string $identifier): Extract|Error
    {
        $response = $this->spaceTraderClient->extract($identifier);

        return $this->denormalizer->denormalize($response->toArray()['data'], Extract::class);
    }

    public function sell(string $identifier, string $inventorySymbol, int $quantity): void
    {
        $response = $this->spaceTraderClient->sell($identifier, $inventorySymbol, $quantity);
    }

    public function deliverContract(string $shipSymbol, string $contractId, string $tradeSymbol, int $units)
    {
        $response = $this->spaceTraderClient->deliverContract($shipSymbol, $contractId, $tradeSymbol, $units);
        dump($response->toArray()['data']);
    }

    public function refuel(string $shipSymbol, int $units)
    {
        $response = $this->spaceTraderClient->refuel($shipSymbol, $units);
        dump($response->toArray()['data']);
    }
}