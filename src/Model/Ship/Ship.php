<?php

namespace App\Model\Ship;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Exception\LimitExtractionException;
use App\State\Processor\Ship\PostShipDockProcessor;
use App\State\Processor\Ship\PostShipExtractProcessor;
use App\State\Processor\Ship\PostShipOrbitProcessor;
use App\State\Provider\Chip\MyShipsProvider;
use Symfony\Component\Serializer\Annotation\Groups;

#[GetCollection(
    uriTemplate: 'my/ships',
    name: 'api_my_ships',
    provider: MyShipsProvider::class
)]
#[Post(
    uriTemplate: '/ships/dock',
    denormalizationContext: ['groups' => self::DOCK],
    name: 'api_dock_ship',
    processor: PostShipDockProcessor::class
)]
#[Post(
    uriTemplate: '/ships/orbit',
    denormalizationContext: ['groups' => self::DOCK],
    name: 'api_orbit_ship',
    processor: PostShipOrbitProcessor::class
)]
#[Post(
    uriTemplate: '/ships/extract',
    denormalizationContext: ['groups' => self::DOCK],
    name: 'api_ship_extract',
    processor: PostShipExtractProcessor::class
)]
class Ship
{
    const DOCK = 'DOCK';
    #[Groups(self::DOCK)]
    #[ApiProperty(identifier: true)]
    public string $symbol;
    public Nav $nav;
    public Registration $registration;
    public Cargo $cargo;
    public Fuel $fuel;
}