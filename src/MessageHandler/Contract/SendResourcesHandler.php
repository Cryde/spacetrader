<?php

namespace App\MessageHandler\Contract;

use App\Message\Contract\SendResources;
use App\Model\Error;
use App\Model\Extract\Extract;
use App\Service\Cache\CacheFactory;
use App\Service\Facade\SpaceTraderFacade;
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
        private readonly CacheFactory $cacheFactory
    ) {
    }

    public function __invoke(SendResources $sendResourcesMessage): void
    {
        try {
            $cache = $this->cacheFactory->create();
            $this->spaceTraderFacade->dockShip($sendResourcesMessage->getShipSymbol());
            sleep(1);
            $this->spaceTraderFacade->deliverContract(
                $sendResourcesMessage->getShipSymbol(),
                $sendResourcesMessage->getContractId(),
                $sendResourcesMessage->getTradSymbol(),
                $sendResourcesMessage->getUnits()
            );
            $cache->invalidateTags([CacheFactory::TAG_MY_CONTRACTS]);
            // todo fuel stuff !
            $this->spaceTraderFacade->orbitShip($sendResourcesMessage->getShipSymbol());
            sleep(1);
            $navigation = $this->spaceTraderFacade->navigate(
                $sendResourcesMessage->getShipSymbol(),
                $sendResourcesMessage->getWaypointSymbolReturn()
            );
            $seconds = $navigation->nav->route->arrival->getTimestamp() - (new \DateTimeImmutable())->getTimestamp();
            $this->bus->dispatch(new Extract(), [
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