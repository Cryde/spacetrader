<?php

namespace App\Message\Extract;
class Extract
{
    public function __construct(private readonly string $symbol)
    {
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}