<?php

namespace App\Model\Ship;
class Cargo
{
    public int $capacity;
    public int $units;
    /** @var Inventory[] */
    public array $inventory;
}