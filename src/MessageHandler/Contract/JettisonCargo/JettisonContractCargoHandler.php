<?php

namespace App\MessageHandler\Contract\JettisonCargo;

use App\Message\Cargo\CargoQuantityCheck;
use App\Message\Jettison\JettisonContractCargo;
use App\Service\Contract\ContractsGoodsProvider;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
readonly class JettisonContractCargoHandler
{
    public function __construct(
        private SpaceTraderFacade      $spaceTraderFacade,
        private ContractsGoodsProvider $contractsGoodsProvider,
        private MessageBusInterface    $bus
    ) {
    }

    public function __invoke(JettisonContractCargo $jettisonContractCargo): void
    {
        // 1. first get contracts to check what we need to exclude from sell
        $contractsGoods = $this->contractsGoodsProvider->getContractsGoods();

        // 2. get the current cargo from this ship
        $ship = $this->spaceTraderFacade->getShip($jettisonContractCargo->symbol);
        sleep(1);
       // $this->spaceTraderFacade->dockShip($sellCargoMessage->getSymbol());
        sleep(1);
        foreach ($ship->cargo->inventory as $inventory) {
            // 3. don't jettison good we need in contracts !
            if (!in_array($inventory->symbol, $contractsGoods)) {
                $this->spaceTraderFacade->jettison(
                    $jettisonContractCargo->symbol,
                    $inventory->symbol,
                    $inventory->units
                );
                sleep(1);
            }
        }

        // Wait 10 sec then check if cargo has enough place to re-extract
        $this->bus->dispatch(new CargoQuantityCheck($jettisonContractCargo->symbol));
    }
}