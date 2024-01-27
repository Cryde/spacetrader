<?php

namespace App\ApiResource\System\Shipyard;
class Ship
{
    public string $type;
    public string $name;
    public string $description;
    public string $supply;
    public string $activity;
    public int $purchasePrice;
    public $crew; // todo
    public $frame; // todo
    public $reactor; // todo
    public $engine; // todo
    public array $modules; // todo
    public array $mounts; // todo
}

