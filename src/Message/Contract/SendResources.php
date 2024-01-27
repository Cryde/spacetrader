<?php

namespace App\Message\Contract;
readonly class SendResources
{
    public function __construct(
        public string $shipSymbol,
        public string $waypointSymbolReturn,
        public string $waypointSymbol,
        public string $contractId,
        public string $tradSymbol,
        public int $units,
    ) {
    }
}