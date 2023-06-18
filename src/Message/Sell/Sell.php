<?php

namespace App\Message\Sell;
class Sell
{
    public function __construct(
        private readonly string $shipSymbol,
        private readonly string $unitSymbol,
        private readonly int    $unitQuantity
    ) {
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }

    public function getUnitSymbol(): string
    {
        return $this->unitSymbol;
    }

    public function getUnitQuantity(): int
    {
        return $this->unitQuantity;
    }
}