<?php

namespace App\Model\Ship;

use ApiPlatform\Metadata\GetCollection;
use App\State\Provider\Chip\MyShipsProvider;

#[GetCollection(
    uriTemplate: 'my/ships',
    name: 'api_my_ships',
    provider: MyShipsProvider::class
)]
class Ship
{
    public string $symbol;
    public Nav $nav;
    public Registration $registration;
    public Cargo $cargo;
}