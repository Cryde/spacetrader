<?php

namespace App\Model\Contract;
class Term
{
    public \DateTimeImmutable $deadline;
    public Payment $payment;
    /** @var Deliver[]  */
    public array $deliver;
}