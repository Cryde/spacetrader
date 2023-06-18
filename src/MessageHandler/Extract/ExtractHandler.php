<?php

namespace App\MessageHandler\Extract;

use App\Contract\ErrorCode;
use App\Message\Extract\Extract;
use App\Message\Sell\SellCargo;
use App\Model\Error;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
class ExtractHandler
{
    public function __construct(
        private readonly SpaceTraderFacade     $spaceTraderFacade,
        private readonly MessageBusInterface   $bus,
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function __invoke(Extract $extractMessage): void
    {
        try {
            $extract = $this->spaceTraderFacade->extract($extractMessage->getSymbol());
            if ($extract->cargo->capacity === $extract->cargo->units) {
                $this->bus->dispatch(new SellCargo($extractMessage->getSymbol()));

                return;
            }
            // todo detect fuel issue !!
            // redispatch an extraction !
            $this->bus->dispatch(new Extract($extractMessage->getSymbol()), [
                new DelayStamp(1000 * $extract->cooldown->remainingSeconds),
            ]);
        } catch (HttpExceptionInterface $e) {
            $errorData = $e->getResponse()->toArray(false)['error'] ?? null;
            dump($errorData);
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            if ($error->code === ErrorCode::SHIP_CARGO_IS_FULL) {
                $this->bus->dispatch(new SellCargo($extractMessage->getSymbol()));

                return;
            }
            if ($error->code === ErrorCode::SHIP_COOL_DOWN) {
                $this->bus->dispatch(new Extract($extractMessage->getSymbol()), [
                    new DelayStamp(1000 * $error->data['cooldown']['remainingSeconds']),
                ]);

                return;
            }
        }
    }
}