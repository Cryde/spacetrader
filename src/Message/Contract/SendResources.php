<?php

namespace App\Message\Contract;
class SendResources
{
    public function __construct(
        private readonly string $shipSymbol,
        private readonly string $waypointSymbolReturn,
        private readonly string $waypointSymbol,
        private readonly string $contractId,
        private readonly string $tradSymbol,
        private readonly int    $units,
    ) {
    }

    public function getShipSymbol(): string
    {
        return $this->shipSymbol;
    }

    public function getWaypointSymbolReturn(): string
    {
        return $this->waypointSymbolReturn;
    }

    public function getWaypointSymbol(): string
    {
        return $this->waypointSymbol;
    }

    public function getContractId(): string
    {
        return $this->contractId;
    }

    public function getTradSymbol(): string
    {
        return $this->tradSymbol;
    }

    public function getUnits(): int
    {
        return $this->units;
    }
}