<?php

namespace App\MessageHandler\Extract;

use App\Contract\ErrorCode;
use App\Message\Extract\Extract;
use App\Message\Sell\SellCargo;
use App\Model\Error;
use App\Service\Facade\SpaceTraderFacade;
use Psr\Log\LoggerInterface;
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
        private readonly DenormalizerInterface $denormalizer,
        private readonly LoggerInterface       $logger
    ) {
    }

    public function __invoke(Extract $extractMessage): void
    {
        try {
            // 1. first we check if the cargo is full
            $ship = $this->spaceTraderFacade->getShip($extractMessage->getSymbol());
            if ($ship->cargo->capacity === $ship->cargo->units) {
                $this->bus->dispatch(new SellCargo($extractMessage->getSymbol()));

                return;
            }
            sleep(1);
            // 2. we extract
            $extract = $this->spaceTraderFacade->extract($extractMessage->getSymbol());
            // 3. if we are full, it's time to sell !
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
            if ($errorData === null) {
                throw $e;
            }
            /** @var Error $error */
            $error = $this->denormalizer->denormalize($errorData, Error::class);
            if ($error->code === ErrorCode::SHIP_CARGO_IS_FULL) {
                $this->logger->error('Ship cargo is full!');
                $this->bus->dispatch(new SellCargo($extractMessage->getSymbol()));

                return;
            }
            if ($error->code === ErrorCode::SHIP_COOL_DOWN) {
                $this->logger->error('Ship has a cooldown');
                $this->bus->dispatch(new Extract($extractMessage->getSymbol()), [
                    new DelayStamp(1000 * $error->data['cooldown']['remainingSeconds']),
                ]);

                return;
            }
            if ($error->code === ErrorCode::NOT_VALID_MINING_SITE) {
                $this->logger->error('Not on a valid site to mine');

                // we do nothing.
                return;
            }
        }
    }
}