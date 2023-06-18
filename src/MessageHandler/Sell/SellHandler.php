<?php

namespace App\MessageHandler\Sell;

use App\Message\Sell\Sell;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SellHandler
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    public function __invoke(Sell $sellMessage): void
    {
        $this->spaceTraderFacade->sell($sellMessage->getShipSymbol(), $sellMessage->getUnitSymbol(), $sellMessage->getUnitQuantity());
    }
}