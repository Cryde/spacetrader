<?php

namespace App\MessageHandler\Contract;

use App\Contract\ErrorCode;
use App\Message\Contract\NavigateContract;
use App\Message\Contract\SendResources;
use App\Model\Error;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class NavigateContractHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly MessageBusInterface   $bus,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function __invoke(NavigateContract $sendResourcesMessage)
    {
        try {
            $this->spaceTraderFacade->orbitShip($sendResourcesMessage->getShipSymbol());
            $navigation = $this->spaceTraderFacade->navigate(
                $sendResourcesMessage->getShipSymbol(),
                $sendResourcesMessage->getWaypointSymbol()
            );
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
            $sendResourcesMessage->getShipSymbol(),
            $sendResourcesMessage->getWaypointSymbolReturn(),
            $sendResourcesMessage->getWaypointSymbol(),
            $sendResourcesMessage->getContractId(),
            $sendResourcesMessage->getTradSymbol(),
            $sendResourcesMessage->getUnits(),
        ), [
            new DelayStamp(1000 * ($seconds + 2)),
        ]);
    }
}