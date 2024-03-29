<?php

namespace App\MessageHandler\Extract;

use App\ApiResource\Error;
use App\Contract\ErrorCode;
use App\Message\Extract\Extract;
use App\Message\Jettison\JettisonContractCargo;
use App\Message\Sell\SellCargo;
use App\Service\Facade\SpaceTraderFacade;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

#[AsMessageHandler]
readonly class ExtractHandler
{
    public function __construct(
        private SpaceTraderFacade     $spaceTraderFacade,
        private MessageBusInterface   $bus,
        private DenormalizerInterface $denormalizer,
        private LoggerInterface       $logger
    ) {
    }

    public function __invoke(Extract $extractMessage): void
    {
        try {
            // 1. first we check if the cargo is full
            $ship = $this->spaceTraderFacade->getShip($extractMessage->symbol);
            if ($ship->cargo->capacity === $ship->cargo->units) {
                $this->bus->dispatch(new SellCargo($extractMessage->symbol));

                return;
            }
            sleep(1);
            // 2. we extract
            $extract = $this->spaceTraderFacade->extract($extractMessage->symbol);

            // todo : implement "strategy" for contract automation (jettison cargo (fast) or sell (slow) for instance)
            // 3. if we are full !
            if ($extract->cargo->capacity === $extract->cargo->units) {
                // A: jettison excess
                $this->bus->dispatch(new JettisonContractCargo($extractMessage->symbol));
                /*
                B: sell excess stuff we have
                todo : rework this !  this need to be more intelligent. Market now have "good" that they accept */
                //$this->bus->dispatch(new SellCargo($extractMessage->symbol));

                return;
            }
            // todo detect fuel issue !!
            // redispatch an extraction !
            $this->bus->dispatch(new Extract($extractMessage->symbol), [
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
                $this->bus->dispatch(new JettisonContractCargo($extractMessage->symbol));
                // todo rework this $this->bus->dispatch(new SellCargo($extractMessage->symbol));

                return;
            }
            if ($error->code === ErrorCode::SHIP_COOL_DOWN) {
                $this->logger->error('Ship has a cooldown');
                $this->bus->dispatch(new Extract($extractMessage->symbol), [
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