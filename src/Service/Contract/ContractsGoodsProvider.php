<?php

namespace App\Service\Contract;

use App\Service\Facade\SpaceTraderFacade;

readonly class ContractsGoodsProvider
{
    public function __construct(
        private SpaceTraderFacade $spaceTraderFacade,
    ) {
    }

    public function getContractsGoods(): array
    {
        $contracts = $this->spaceTraderFacade->getContracts();
        $contractGoodSymbols = [];
        foreach ($contracts as $contract) {
            if ($contract->accepted && !$contract->fulfilled) {
                foreach ($contract->terms->deliver as $deliver) {
                    $contractGoodSymbols[] = $deliver->tradeSymbol;
                }
            }
        }

        return $contractGoodSymbols;
    }
}