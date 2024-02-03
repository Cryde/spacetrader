<?php

namespace App\Message\Sell;
readonly class Sell
{
    public function __construct(
        public string $shipSymbol,
        public string $unitSymbol,
        public int    $unitQuantity
    ) {
    }
}