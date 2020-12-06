<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @UniqueEntity(fields={"username"})
 */
class User implements UserInterface, \Serializable {

    use IdTrait;
    use UuidTrait;

    /**
     * @ORM\Column(type="uuid")
     * @var UuidInterface
     */
    private $idpId;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(allowNull=true)
     * @var string|null
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank(allowNull=true)
     * @var string|null
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email()
     * @Assert\NotBlank(allowNull=true)
     * @var string|null
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $kivutoFirstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $kivutoLastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $kivutoEmail;

    /**
     * @ORM\Column(type="json")
     * @var string[]
     */
    private $roles = ['ROLE_USER'];

    public function __construct() {
        $this->uuid = Uuid::uuid4();
    }

    /**
     * @return UuidInterface|null
     */
    public function getIdpId(): ?UuidInterface {
        return $this->idpId;
    }

    /**
     * @param UuidInterface $uuid
     * @return User
     */
    public function setIdpId(UuidInterface $uuid): User {
        $this->idpId = $uuid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return User
     */
    public function setFirstname(?string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     * @return User
     */
    public function setLastname(?string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return User
     */
    public function setEmail(?string $email): User {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string[] $roles
     * @return User
     */
    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @return string|null
     */
    public function getKivutoFirstname(): ?string {
        return $this->kivutoFirstname;
    }

    /**
     * @param string|null $kivutoFirstname
     * @return User
     */
    public function setKivutoFirstname(?string $kivutoFirstname): User {
        $this->kivutoFirstname = $kivutoFirstname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKivutoLastname(): ?string {
        return $this->kivutoLastname;
    }

    /**
     * @param string|null $kivutoLastname
     * @return User
     */
    public function setKivutoLastname(?string $kivutoLastname): User {
        $this->kivutoLastname = $kivutoLastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKivutoEmail(): ?string {
        return $this->kivutoEmail;
    }

    /**
     * @param string|null $kivutoEmail
     * @return User
     */
    public function setKivutoEmail(?string $kivutoEmail): User {
        $this->kivutoEmail = $kivutoEmail;
        return $this;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() { }

    /**
     * @inheritDoc
     */
    public function serialize() {
        return serialize([
            $this->getId(),
            $this->getUsername()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized) {
        list($this->id, $this->username) = unserialize($serialized);
    }
}