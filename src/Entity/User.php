<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
class User implements UserInterface {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $username;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [ 'ROLE_USER' ];

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     * @return User
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    // -------------------------------------------

    /**
     * @return string
     */
    public function getPassword() {
        return null;
    }

    /**
     * @return string|null
     */
    public function getSalt() {
        return null;
    }

    public function eraseCredentials() {
    }
}