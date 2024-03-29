<?php

namespace App\Contract;
class ErrorCode
{
    final const SHIP_CARGO_IS_FULL = 4228;
    final const SHIP_COOL_DOWN = 4000;
    final const FAILED_TO_PARSE_TOKEN = 4100;
    final const SYMBOL_AGENT_ALREADY_TAKEN = 4111;
    final const SHIP_LOCATED_AT_LOCATION = 4204;
    final const SHIP_IN_TRANSIT = 4214;
    final const NOT_VALID_MINING_SITE = 4205;
}