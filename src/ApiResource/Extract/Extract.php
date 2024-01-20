<?php

namespace App\ApiResource\Extract;

use App\ApiResource\Ship\Cargo;

class Extract
{
    public Extraction $extraction;
    public CoolDown $cooldown;
    public Cargo $cargo;
}