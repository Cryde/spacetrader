<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Ship\Ship;
use App\Exception\LimitExtractionException;
use App\Message\Extract\Extract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class PostShipExtractProcessor implements ProcessorInterface
{
    public function __construct(
        //    private readonly SpaceTraderFacade $spaceTraderFacade
        private readonly MessageBusInterface $bus
    ) {
    }

    /**
     * @param Ship $data
     *
     * @throws LimitExtractionException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        try {
            $this->bus->dispatch(new Extract($data->symbol));
            //dump($this->spaceTraderFacade->extract($data->symbol));
        } catch (HttpExceptionInterface $e) {
            if ($e->getCode() === Response::HTTP_CONFLICT) {
                throw new LimitExtractionException();
            }
        }

        return null;
    }
}