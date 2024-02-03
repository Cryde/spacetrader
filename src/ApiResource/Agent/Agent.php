<?php

namespace App\ApiResource\Agent;

use ApiPlatform\Metadata\Get;
use App\State\Provider\Agent\AgentProvider;

#[Get(
    name: 'api_my_agent',
    provider: AgentProvider::class
)]
class Agent
{
    public string $accountId;
    public string $symbol;
    public string $headquarters;
    public int $credits;
    public string $startingFaction;
}