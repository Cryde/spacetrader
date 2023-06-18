<?php

namespace App\Model\Ship;
class Fuel
{
    const LIMIT_PERCENT_REFUEL = 50;
    public int $current;
    public int $capacity;

    public function isRefuelNeeded(): bool
    {
        if ($this->capacity === 0) {
            return false; // surveryor doesn't have fueld
        }
        $percent = $this->current / ($this->capacity / 100);

        return $percent < self::LIMIT_PERCENT_REFUEL;
    }

    public function neededFuel(): int
    {
        return $this->capacity - $this->current;
    }
}