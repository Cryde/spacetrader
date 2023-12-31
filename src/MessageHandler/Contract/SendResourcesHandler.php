<?php

namespace App\MessageHandler\Contract;

use App\Message\Contract\SendResources;
use App\Message\Extract\Extract;
use App\Model\Error;
use App\Service\Cache\CacheFactory;
use App\Service\Facade\SpaceTraderFacade;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class SendResourcesHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly MessageBusInterface   $bus,
        private readonly DenormalizerInterface $denormalizer,
        private readonly CacheFactory $cacheFactory,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(SendResources $sendResourcesMessage): void
    {
        $shipSymbol = $sendResourcesMessage->getShipSymbol();
        try {
            $cache = $this->cacheFactory->create();
            $this->spaceTraderFacade->dockShip($shipSymbol);
            sleep(1);
            $this->spaceTraderFacade->deliverContract(
                $shipSymbol,
                $sendResourcesMessage->getContractId(),
                $sendResourcesMessage->getTradSymbol(),
                $sendResourcesMessage->getUnits()
            );
            $cache->invalidateTags([CacheFactory::TAG_MY_CONTRACTS]);
            $ship = $this->spaceTraderFacade->getShip($shipSymbol);
            if ($ship->fuel->isRefuelNeeded()) {
                $this->logger->alert('Ship {shipSymbol} need to be refueld', ['shipSymbol' => $shipSymbol]);
                // we need to be docked to be refuel
                $this->spaceTraderFacade->refuel($shipSymbol, $ship->fuel->neededFuel());
                sleep(1);
            }
            $this->spaceTraderFacade->orbitShip($shipSymbol);
            sleep(1);
            $navigation = $this->spaceTraderFacade->navigate(
                $shipSymbol,
                $sendResourcesMessage->getWaypointSymbolReturn()
            );
            $seconds = $navigation->nav->route->arrival->getTimestamp() - (new \DateTimeImmutable())->getTimestamp();
            $this->bus->dispatch(new Extract(
                $shipSymbol
            ), [
                new DelayStamp(1000 * ($seconds + 2)),
            ]);
        } catch (HttpExceptionInterface $e) {
            $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            dump('ERROR IN SendResourcesHandler');
            dump($error);
        }
    }
}