<?php

namespace App\ApiResource\Extract;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Extraction
{
    public string $shipSymbol;
    #[SerializedName('yield')]
    public Returned $returned;
}