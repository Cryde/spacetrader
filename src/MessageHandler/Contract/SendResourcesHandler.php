<?php

namespace App\MessageHandler\Contract;

use App\Message\Contract\SendResources;
use App\Model\Error;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class SendResourcesHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function __invoke(SendResources $sendResourcesMessage): void
    {
        dump('--------------------------------------------------------');
        dump('SendResourcesHandler');
        dump('--------------------------------------------------------');
        try {
            $this->spaceTraderFacade->deliverContract(
                $sendResourcesMessage->getShipSymbol(),
                $sendResourcesMessage->getContractId(),
                $sendResourcesMessage->getTradSymbol(),
                $sendResourcesMessage->getUnits()
            );
        } catch (HttpExceptionInterface $e) {
            $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            dump($error);
        }
        $navigation = $this->spaceTraderFacade->navigate(
            $sendResourcesMessage->getShipSymbol(),
            $sendResourcesMessage->getWaypointSymbolReturn()
        );

        $seconds = $navigation->nav->route->arrival->getTimestamp() - (new \DateTimeImmutable())->getTimestamp();

        dump($seconds);
    }
}