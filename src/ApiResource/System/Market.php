<?php

namespace App\ApiResource\System;

use ApiPlatform\Metadata\GetCollection;
use App\ApiResource\System\Market\Exchange;
use App\ApiResource\System\Market\Export;
use App\ApiResource\System\Market\Import;
use App\ApiResource\System\Market\TradeGood;
use App\ApiResource\System\Market\Transaction;
use App\State\Provider\System\MarketProvider;

#[GetCollection(
    uriTemplate: 'systems/{systemSymbol}/waypoints/{waypointSymbol}/market',
    name: 'api_market_get',
    provider: MarketProvider::class
)]
class Market
{
    public string $symbol;
    /** @var Export[] */
    public array $exports;
    /** @var Import[] */
    public array $imports;
    /** @var Exchange[] */
    public array $exchange;
    /** @var Transaction[] */
    public array $transactions;
    /** @var TradeGood[] */
    public array $tradeGoods;
}
