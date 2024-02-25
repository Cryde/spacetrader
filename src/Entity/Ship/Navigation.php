<?php

namespace App\Entity\Ship;

use App\Repository\Ship\NavigationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NavigationRepository::class)]
class Navigation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDatetime = null;

    #[ORM\Column(length: 255)]
    private ?string $shipSymbol = null;

    #[ORM\Column]
    private array $origin = [];

    #[ORM\Column]
    private array $destination = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureDatetime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $arrivalDatetime = null;

    #[ORM\Column(length: 255)]
    private ?string $flightMode = null;

    public function __construct()
    {
        $this->creationDatetime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShipSymbol(): ?string
    {
        return $this->shipSymbol;
    }

    public function setShipSymbol(string $shipSymbol): static
    {
        $this->shipSymbol = $shipSymbol;

        return $this;
    }

    public function getOrigin(): array
    {
        return $this->origin;
    }

    public function setOrigin(array $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDestination(): array
    {
        return $this->destination;
    }

    public function setDestination(array $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDepartureDatetime(): ?\DateTimeInterface
    {
        return $this->departureDatetime;
    }

    public function setDepartureDatetime(\DateTimeInterface $departureDatetime): static
    {
        $this->departureDatetime = $departureDatetime;

        return $this;
    }

    public function getArrivalDatetime(): ?\DateTimeInterface
    {
        return $this->arrivalDatetime;
    }

    public function setArrivalDatetime(\DateTimeInterface $arrivalDatetime): static
    {
        $this->arrivalDatetime = $arrivalDatetime;

        return $this;
    }

    public function getFlightMode(): ?string
    {
        return $this->flightMode;
    }

    public function setFlightMode(string $flightMode): static
    {
        $this->flightMode = $flightMode;

        return $this;
    }
}
