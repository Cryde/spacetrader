<?php

namespace App\Model\Ship;

use App\Model\Navigation\Route;

class Nav
{
    public string $systemSymbol;
    public string $waypointSymbol;
    public string $status;
    public Route $route;
}