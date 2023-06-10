<?php

namespace App\Service\Facade;

use App\Model\Agent;
use App\Service\Client\SpaceTraderClient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SpaceTraderFacade
{
    public function __construct(
        private readonly SpaceTraderClient     $spaceTraderClient,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function getAgent()
    {
        $response = $this->spaceTraderClient->getAgent();

        return $this->denormalizer->denormalize($response->toArray()['data'], Agent::class);
    }
}