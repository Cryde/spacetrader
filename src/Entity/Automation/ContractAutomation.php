<?php

namespace App\Entity\Automation;

use App\Repository\Automation\ContractAutomationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractAutomationRepository::class)]
class ContractAutomation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contractId = null;

    #[ORM\Column]
    private ?int $strategy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDatetime = null;

    public function __construct()
    {
        $this->creationDatetime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContractId(): ?string
    {
        return $this->contractId;
    }

    public function setContractId(string $contractId): static
    {
        $this->contractId = $contractId;

        return $this;
    }

    public function getStrategy(): ?int
    {
        return $this->strategy;
    }

    public function setStrategy(int $strategy): static
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getCreationDatetime(): ?\DateTimeInterface
    {
        return $this->creationDatetime;
    }

    public function setCreationDatetime(\DateTimeInterface $creationDatetime): static
    {
        $this->creationDatetime = $creationDatetime;

        return $this;
    }
}
