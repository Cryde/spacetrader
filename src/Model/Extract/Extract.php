<?php

namespace App\Model\Extract;

use App\Model\Ship\Cargo;

class Extract
{
    public Extraction $extraction;
    public CoolDown $cooldown;
    public Cargo $cargo;
}