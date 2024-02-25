<?php

namespace App\ApiResource\Ship;

use App\ApiResource\Navigation\Route;

class Nav
{
    public string $systemSymbol;
    public string $waypointSymbol;
    public string $status;
    public string $flightMode;
    public Route $route;
}