<?php

namespace App\Message\Cargo;
class CargoQuantityCheck
{
    public function __construct(private readonly string $shipSymbol)
    {
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }
}