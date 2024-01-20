<?php

namespace App\MessageHandler\Sell;

use App\Message\Cargo\CargoQuantityCheck;
use App\Message\Sell\Sell;
use App\Message\Sell\SellCargo;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

#[AsMessageHandler]
class SellCargoHandler
{
    public function __construct(
        private readonly SpaceTraderFacade   $spaceTraderFacade,
        private readonly MessageBusInterface $bus
    ) {
    }

    public function __invoke(SellCargo $sellCargoMessage): void
    {
        // 1. first get contracts to check what we need to exclude from sell
        $contracts = $this->spaceTraderFacade->getContracts();
        $excludedFromSells = [];
        foreach ($contracts as $contract) {
            if ($contract->accepted && !$contract->fulfilled) {
                foreach ($contract->terms->deliver as $deliver) {
                    $excludedFromSells[] = $deliver->tradeSymbol;
                }
            }
        }
        // 2. get the current cargo from this ship
        $ship = $this->spaceTraderFacade->getShip($sellCargoMessage->getSymbol());
        sleep(1);
        $this->spaceTraderFacade->dockShip($sellCargoMessage->getSymbol());
        sleep(1);
        foreach ($ship->cargo->inventory as $inventory) {
            // 3. don't sell good we need in contracts !
            if (!in_array($inventory->symbol, $excludedFromSells)) {
                $this->bus->dispatch(new Sell(
                    $sellCargoMessage->getSymbol(),
                    $inventory->symbol,
                    $inventory->units
                ));
                sleep(1);
            }
        }

        // Wait 10 sec then check if cargo has enough place to re-extract
        $this->bus->dispatch(new CargoQuantityCheck($sellCargoMessage->getSymbol()), [
            new DelayStamp(1000 * 10)
        ]);
    }
}