<?php

namespace App\State\Processor\Ship;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Exception\LimitExtractionException;
use App\Model\Ship\Ship;
use App\Service\Facade\SpaceTraderFacade;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

class PostShipExtractProcessor implements ProcessorInterface
{
    public function __construct(private readonly SpaceTraderFacade $spaceTraderFacade)
    {
    }

    /**
     * @param Ship $data
     *
     * @throws LimitExtractionException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        try {
            $this->spaceTraderFacade->extract($data->symbol);
        } catch (HttpExceptionInterface $e) {
            if ($e->getCode() === Response::HTTP_CONFLICT) {
                throw new LimitExtractionException();
            }
        }

        return null;
    }
}