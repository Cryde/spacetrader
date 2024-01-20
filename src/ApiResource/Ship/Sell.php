<?php

namespace App\ApiResource\Ship;

use ApiPlatform\Metadata\Post;
use App\State\Processor\Ship\PostShipSellProcessor;

#[Post(
    uriTemplate: '/ships/sell',
    name: 'api_ship_sell',
    processor: PostShipSellProcessor::class
)]
class Sell
{
    public string $shipSymbol;
    public string $unitSymbol;
    public int $amount;
}