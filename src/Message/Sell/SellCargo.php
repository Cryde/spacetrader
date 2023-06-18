<?php

namespace App\Message\Sell;
class SellCargo
{
    public function __construct(private readonly string $symbol)
    {
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}