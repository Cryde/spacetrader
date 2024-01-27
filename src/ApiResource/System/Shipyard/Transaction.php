<?php

namespace App\ApiResource\System\Shipyard;
class Transaction
{
    public string $waypointSymbol;
    public string $shipSymbol;
    public string $shipType;
    public int $price;
    public string $agentSymbol;
    public \DateTime $timestamp;
}
