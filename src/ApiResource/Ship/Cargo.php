<?php

namespace App\ApiResource\Ship;
class Cargo
{
    public int $capacity;
    public int $units;
    /** @var Inventory[] */
    public array $inventory;
}