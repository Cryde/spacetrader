<?php

namespace App\State\Provider\System;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Service\Facade\SpaceTraderFacade;
use Doctrine\Common\Collections\ArrayCollection;

readonly class WaypointCollectionProvider implements ProviderInterface
{
    public function __construct(public SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $systemSymbol = $uriVariables['systemSymbol'];

        $traits = $context['filters']['traits'] ?? null;
        $type = $context['filters']['type'] ?? null;
        $page = $context['filters']['page'] ?? 1;

        $result = $this->spaceTraderFacade->getWaypointsBySystemSymbol($systemSymbol, $page, $traits, $type);

        return new TraversablePaginator(
            new ArrayCollection($result->data),
            $result->meta->page,
            10,
            $result->meta->total
        );
    }
}