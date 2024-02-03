<?php

namespace App\MessageHandler\Contract;

use App\ApiResource\Error;
use App\Contract\ErrorCode;
use App\Message\Contract\NavigateContract;
use App\Message\Contract\SendResources;
use App\Service\Facade\SpaceTraderFacade;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
readonly class NavigateContractHandler
{
    public function __construct(
        private SpaceTraderFacade     $spaceTraderFacade,
        private MessageBusInterface   $bus,
        private DenormalizerInterface $denormalizer,
        private LoggerInterface       $logger
    ) {
    }

    public function __invoke(NavigateContract $sendResourcesMessage): void
    {
        $shipSymbol = $sendResourcesMessage->shipSymbol;
        try {
            $ship = $this->spaceTraderFacade->getShip($shipSymbol);
            if ($ship->fuel->isRefuelNeeded()) {
                $this->logger->alert('Ship {shipSymbol} need to be refueld', ['shipSymbol' => $shipSymbol]);
                // we need to be docked to be refuel
                $this->spaceTraderFacade->refuel($shipSymbol, $ship->fuel->neededFuel());
                sleep(1);
            }

            $this->spaceTraderFacade->orbitShip($shipSymbol);
            $this->logger->info('Ship {shipSymbol} put in orbit', ['shipSymbol' => $shipSymbol]);
            $navigation = $this->spaceTraderFacade->navigate(
                $shipSymbol,
                $sendResourcesMessage->waypointSymbol
            );
            $this->logger->info('Ship {shipSymbol} is navigating to {ws}', [
                'shipSymbol' => $shipSymbol,
                'ws' => $sendResourcesMessage->waypointSymbol
            ]);
            $seconds = $navigation->nav->route->arrival->getTimestamp() - (new \DateTimeImmutable())->getTimestamp();
        } catch (HttpExceptionInterface $e) {
            $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            dump($error);
            // in that case we are arrived we can send ze resources
            if ($error->code === ErrorCode::SHIP_LOCATED_AT_LOCATION) {
                $seconds = -1;
            } else if ($error->code === ErrorCode::SHIP_IN_TRANSIT) {
                $seconds = $error->data['secondsToArrival'] + 3; // 3 is a margin
            } else {
                dump('no handled yet');

                return;
            }
        }
        if ($seconds < 0) {
            $seconds = 1;
        }
        $this->bus->dispatch(new SendResources(
            $shipSymbol,
            $sendResourcesMessage->waypointSymbolReturn,
            $sendResourcesMessage->waypointSymbol,
            $sendResourcesMessage->contractId,
            $sendResourcesMessage->tradSymbol,
            $sendResourcesMessage->units,
        ), [
            new DelayStamp(1000 * ($seconds + 2)),
        ]);
    }
}