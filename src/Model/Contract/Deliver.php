<?php

namespace App\Model\Contract;
class Deliver
{
    public string $tradeSymbol;
    public string $destinationSymbol;
    public int $unitsRequired;
    public int $unitsFulfilled;
}