<?php

namespace App\ApiResource\System\Market;
class Transaction
{
    public string $waypointSymbol;
    public string $shipSymbol;
    public string $tradeSymbol;
    public string $type;
    public int $units;
    public int $pricePerUnit;
    public int $totalPrice;
    public \DateTime $timestamp;
}