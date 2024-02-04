<?php

namespace App\Entity\User;

use App\Repository\User\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToMany(mappedBy: 'userReference', targetEntity: UserToken::class, orphanRemoval: true)]
    private Collection $tokens;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDatetime = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $spaceTraderTokenApi = null;

    public function __construct()
    {
        $this->tokens = new ArrayCollection();
        $this->creationDatetime = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, UserToken>
     */
    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function addToken(UserToken $token): static
    {
        if (!$this->tokens->contains($token)) {
            $this->tokens->add($token);
            $token->setUserReference($this);
        }

        return $this;
    }

    public function removeToken(UserToken $token): static
    {
        if ($this->tokens->removeElement($token)) {
            // set the owning side to null (unless already changed)
            if ($token->getUserReference() === $this) {
                $token->setUserReference(null);
            }
        }

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

    public function getSpaceTraderTokenApi(): ?string
    {
        return $this->spaceTraderTokenApi;
    }

    public function setSpaceTraderTokenApi(string $spaceTraderTokenApi): static
    {
        $this->spaceTraderTokenApi = $spaceTraderTokenApi;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
