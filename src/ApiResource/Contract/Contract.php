<?php

namespace App\ApiResource\Contract;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\Provider\Contract\ContractProvider;
use App\State\Provider\Contract\MyContractProvider;

#[GetCollection(
    uriTemplate: 'my/contracts',
    name: 'api_my_contracts',
    provider: MyContractProvider::class
)]
#[Get(
    name: 'api_get_contract',
    provider: ContractProvider::class
)]
class Contract
{
    #[ApiProperty(identifier: true)]
    public string $id;
    public string $factionSymbol;
    public string $type;
    public Term $terms;
    public bool $accepted;
    public bool $fulfilled;
    public \DateTimeImmutable $expiration;
    public \DateTimeImmutable $deadlineToAccept;
}