<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['username'])]
class User implements UserInterface {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidInterface $idpId;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $username;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    private string $firstname;

    #[ORM\Column(type: Types::STRING, nullable: false)]
    #[Assert\NotBlank]
    private string $lastname;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Email]
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON, nullable: false)]
    private array $roles = ['ROLE_USER'];

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    public function getIdpId(): ?UuidInterface {
        return $this->idpId;
    }

    public function setIdpId(UuidInterface $uuid): User {
        $this->idpId = $uuid;
        return $this;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(?string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array {
        return $this->roles;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return '';
    }

    public function eraseCredentials(): void { }

    public function serialize(): ?string {
        return serialize([
            $this->getId(),
            $this->getUserIdentifier()
        ]);
    }

    public function unserialize(string $serialized): void {
        [$this->id, $this->username] = unserialize($serialized);
    }
}