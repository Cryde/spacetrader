<?php

namespace App\Message\Cargo;
readonly class CargoQuantityCheck
{
    public function __construct(public string $shipSymbol)
    {
    }
}