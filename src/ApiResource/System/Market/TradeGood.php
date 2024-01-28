<?php

namespace App\ApiResource\System\Market;
class TradeGood
{
    public string $symbol;
    public string $type;
    public int $tradeVolume;
    public string $supply;
    public string $activity;
    public int $purchasePrice;
    public int $sellPrice;
}