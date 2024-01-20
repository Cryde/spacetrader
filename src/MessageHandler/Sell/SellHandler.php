<?php

namespace App\MessageHandler\Sell;

use App\ApiResource\Error;
use App\Message\Sell\Sell;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class SellHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function __invoke(Sell $sellMessage): void
    {
        try {
            $this->spaceTraderFacade->sell($sellMessage->getShipSymbol(), $sellMessage->getUnitSymbol(), $sellMessage->getUnitQuantity());
        } catch (HttpExceptionInterface $e) {
            $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            dump($error);
        }
    }
}