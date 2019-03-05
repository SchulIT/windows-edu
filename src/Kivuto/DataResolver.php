<?php

namespace App\Kivuto;

use LightSaml\SpBundle\Security\Authentication\Token\SamlSpToken;
use SchoolIT\KivutoBundle\User\DataResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DataResolver implements DataResolverInterface {

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return SamlSpToken
     * @throws \Exception
     */
    private function getToken() {
        $token = $this->tokenStorage->getToken();

        if(!$token instanceof SamlSpToken) {
            throw new \Exception(sprintf('Token must be of type "%s" ("%s" given)', SamlSpToken::class, get_class($token)));
        }

        return $token;
    }

    public function getUsername() {
        return $this->getToken()->getUsername();
    }

    public function getFirstname() {
        return $this->getToken()->getAttribute('firstname');
    }

    public function getLastname() {
        return $this->getToken()->getAttribute('lastname');
    }

    public function getAcademicStatus() {
        return 'students';
    }

    public function getEmail() {
        return $this->getToken()->getAttribute('email');
    }
}