<?php

namespace App\Entity;

use App\EntityInterface\Timestamp\EntityTimestampTrait;
use App\EntityInterface\Timestamp\EntityTimestampInterface;
use App\EventListener\User\HasChangedPasswordListener;
use App\Repository\UserRepository;
use App\Enum\CivilityEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([HasChangedPasswordListener::class])]
class User implements PasswordAuthenticatedUserInterface, EntityTimestampInterface
{
    use EntityTimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?CivilityEnum $civility = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $isActif = null;

    #[ORM\Column]
    private ?bool $isMember = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastConnectionAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->isActif = true;
        $this->isMember = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFullname(): ?string
    {
        return implode(' ', [$this->getFirstname(), $this->getLastname()]);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function isMember(): ?bool
    {
        return $this->isMember;
    }

    public function setIsMember(bool $isMember): self
    {
        $this->isMember = $isMember;

        return $this;
    }

    public function getLastConnectionAt(): ?\DateTimeImmutable
    {
        return $this->lastConnectionAt;
    }

    public function setLastConnectionAt(?\DateTimeImmutable $lastConnectionAt): self
    {
        $this->lastConnectionAt = $lastConnectionAt;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): self
    {
        $this->password = microtime();
        $this->plainPassword = $password;

        return $this;
    }

    public function getCivility(): ?CivilityEnum
    {
        return $this->civility;
    }

    public function setCivility(?CivilityEnum $civility): self
    {
        $this->civility = $civility;

        return $this;
    }
}
