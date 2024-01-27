<?php

namespace App\MessageHandler\Contract;

use App\ApiResource\Error;
use App\Message\Contract\SendResources;
use App\Message\Extract\Extract;
use App\Service\Cache\CacheFactory;
use App\Service\Facade\SpaceTraderFacade;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class SendResourcesHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly MessageBusInterface   $bus,
        private readonly DenormalizerInterface $denormalizer,
        private readonly TagAwareCacheInterface $cache,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(SendResources $sendResourcesMessage): void
    {
        $shipSymbol = $sendResourcesMessage->shipSymbol;
        try {
            $this->spaceTraderFacade->dockShip($shipSymbol);
            sleep(1);
            $this->spaceTraderFacade->deliverContract(
                $shipSymbol,
                $sendResourcesMessage->contractId,
                $sendResourcesMessage->tradSymbol,
                $sendResourcesMessage->units
            );
            $this->cache->invalidateTags([CacheFactory::TAG_MY_CONTRACTS]);
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
                $sendResourcesMessage->waypointSymbolReturn
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