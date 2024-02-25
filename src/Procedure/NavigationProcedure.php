<?php

namespace App\Procedure;

use App\ApiResource\Navigation\Navigation;
use App\ApiResource\Ship\ShipNavigate;
use App\Builder\Ship\NavigationEntityBuilder;
use App\Service\Facade\SpaceTraderFacade;
use Doctrine\ORM\EntityManagerInterface;

readonly class NavigationProcedure
{
    public function __construct(
        private SpaceTraderFacade       $spaceTraderFacade,
        private NavigationEntityBuilder $navigationEntityBuilder,
        private EntityManagerInterface  $entityManager
    ) {
    }

    public function navigate(ShipNavigate $shipNavigate): Navigation
    {
        $navigation = $this->spaceTraderFacade->navigate($shipNavigate->shipSymbol, $shipNavigate->waypointSymbol);

        $entity = $this->navigationEntityBuilder->buildFromModel($shipNavigate->shipSymbol, $navigation);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        // todo : dispatch message that will message the front that ship is arrived

        return $navigation;
    }
}
