<?php

namespace App\Message\Sell;
readonly class SellCargo
{
    public function __construct(public string $symbol)
    {
    }
}