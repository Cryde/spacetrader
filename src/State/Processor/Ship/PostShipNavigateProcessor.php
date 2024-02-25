<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Ship\ShipNavigate;
use App\Procedure\NavigationProcedure;

readonly class PostShipNavigateProcessor implements ProcessorInterface
{
    public function __construct(private NavigationProcedure $navigationProcedure)
    {
    }

    /**
     * @param ShipNavigate $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        return $this->navigationProcedure->navigate($data);
    }
}