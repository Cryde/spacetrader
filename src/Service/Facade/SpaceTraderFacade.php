<?php

namespace App\Service\Facade;

use App\Model\Agent;
use App\Model\Contract\Contract;
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
}