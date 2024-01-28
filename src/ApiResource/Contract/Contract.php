<?php

namespace App\ApiResource\Contract;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Contract\Fulfill\ContractFulfill;
use App\State\Processor\Contract\ContractFulfillProcessor;
use App\State\Processor\Contract\ContractPostProcessor;
use App\State\Provider\Contract\ContractProvider;
use App\State\Provider\Contract\MyContractProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[GetCollection(
    uriTemplate: 'my/contracts',
    name: 'api_my_contracts',
    provider: MyContractProvider::class
)]
#[Get(
    name: 'api_get_contract',
    provider: ContractProvider::class
)]
#[Post(
    uriTemplate: 'my/contract/fulfill',
    input: ContractFulfill::class,
    name: 'api_fulfill_contract',
    processor: ContractFulfillProcessor::class
)]
#[Post(
    // Post because the contract doesn't really exist for us
    // it's just from a list of pending contract
    uriTemplate: 'my/contract/accept',
    denormalizationContext: ['groups' => Contract::POST],
    name: 'api_post_contract',
    processor: ContractPostProcessor::class
)]
class Contract
{
    public const POST = 'CONTRACT_POST';
    #[ApiProperty(identifier: true)]
    #[Groups([Contract::POST])]
    public string $id;
    public string $factionSymbol;
    public string $type;
    public Term $terms;
    public bool $accepted;
    public bool $fulfilled;
    public \DateTimeImmutable $expiration;
    public \DateTimeImmutable $deadlineToAccept;
}