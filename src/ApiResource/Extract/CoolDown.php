<?php

namespace App\ApiResource\Extract;
class CoolDown
{
    public string $shipSymbol;
    public int $totalSeconds;
    public int $remainingSeconds;
    public \DateTimeImmutable $expiration;
}