<?php

namespace App\ApiResource\Navigation;
class Route
{
    public \DateTimeImmutable $arrival;
    public \DateTimeImmutable $departureTime;
    public RouteItem $origin;
    public RouteItem $destination;
}