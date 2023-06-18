<?php

namespace App\MessageHandler\Cargo;

use App\Message\Cargo\CargoQuantityCheck;
use App\Message\Contract\NavigateContract;
use App\Message\Extract\Extract;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class CargoQuantityCheckHandler
{
    public function __construct(
        private readonly SpaceTraderFacade   $spaceTraderFacade,
        private readonly MessageBusInterface $bus
    ) {
    }

    public function __invoke(CargoQuantityCheck $cargoQuantityCheckMessage): void
    {
        $shipSymbol = $cargoQuantityCheckMessage->getShipSymbol();
        $ship = $this->spaceTraderFacade->getShip($shipSymbol);
        if ($ship->cargo->capacity > $ship->cargo->units) {
            $this->spaceTraderFacade->orbitShip($shipSymbol);
            sleep(1);
            $this->bus->dispatch(new Extract($shipSymbol));

            return;
        }
        $contractSymbols = $this->getContractsSymbols();
        // pour l'instant on traite que le cas ou le ship est rempli
        if ($ship->cargo->capacity === $ship->cargo->units) {
            foreach ($ship->cargo->inventory as $inventory) {
                if (isset($contractSymbols[$inventory->symbol])) {
                    $this->bus->dispatch(new NavigateContract(
                        $shipSymbol,
                        $ship->nav->waypointSymbol,
                        $contractSymbols[$inventory->symbol]['destination'],
                        $contractSymbols[$inventory->symbol]['contract_id'],
                        $inventory->symbol,
                        $inventory->units
                    ));
                }
            }
        }
    }

    private function getContractsSymbols(): array
    {
        $result = [];
        $contracts = $this->spaceTraderFacade->getContracts();
        foreach ($contracts as $contract) {
            if ($contract->accepted) {
                foreach ($contract->terms->deliver as $deliver) {
                    // possible soucis: si 2 contract on le même trade symbol
                    $result[$deliver->tradeSymbol] = ['destination' => $deliver->destinationSymbol, 'contract_id' => $contract->id];
                }
            }
        }

        return $result;
    }
}