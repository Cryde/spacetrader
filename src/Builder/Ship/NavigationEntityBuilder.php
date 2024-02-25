<?php

namespace App\Builder\Ship;

use App\ApiResource\Navigation\Navigation as NavigationModel;
use App\Entity\Ship\Navigation;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class NavigationEntityBuilder
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    public function buildFromModel(string $shipSymbol, NavigationModel $navigationModel)
    {
        return (new Navigation())
            ->setShipSymbol($shipSymbol)
            ->setDestination($this->normalizer->normalize($navigationModel->nav->route->destination))
            ->setOrigin($this->normalizer->normalize($navigationModel->nav->route->origin))
            ->setDepartureDatetime($navigationModel->nav->route->departureTime)
            ->setArrivalDatetime($navigationModel->nav->route->arrival)
            ->setFlightMode($navigationModel->nav->flightMode);
    }
}