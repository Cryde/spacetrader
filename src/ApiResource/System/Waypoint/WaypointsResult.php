<?php

namespace App\ApiResource\System\Waypoint;

use App\ApiResource\System\Meta\Meta;
use Symfony\Component\Serializer\Attribute\SerializedName;

class WaypointsResult
{
    #[SerializedName('waypoints')]
    public array $data;
    public Meta $meta;
}