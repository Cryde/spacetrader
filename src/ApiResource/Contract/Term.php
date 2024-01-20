<?php

namespace App\ApiResource\Contract;
class Term
{
    public \DateTimeImmutable $deadline;
    public Payment $payment;
    /** @var Deliver[]  */
    public array $deliver;
}