<?php

namespace App\Kivuto;

use App\Entity\User;
use Exception;
use LightSaml\SpBundle\Security\Authentication\Token\SamlSpToken;
use SchulIT\KivutoBundle\User\DataResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DataResolver implements DataResolverInterface {

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return SamlSpToken
     * @throws Exception
     */
    private function getToken() {
        $token = $this->tokenStorage->getToken();

        if(!$token instanceof SamlSpToken) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', SamlSpToken::class, get_class($token)));
        }

        return $token;
    }

    /**
     * @return User
     */
    private function getUser(): User {
        $token = $this->getToken();
        $user = $token->getUser();

        if(!$user instanceof User) {
            throw new Exception(sprintf('User must be of type "%s" ("%s" given)', User::class, get_class($user)));
        }

        return $user;
    }

    public function getUsername() {
        return $this->getUser()->getKivutoEmail();
    }

    public function getFirstname() {
        return $this->getUser()->getKivutoFirstname();
    }

    public function getLastname() {
        return $this->getUser()->getKivutoLastname();
    }

    public function getAcademicStatus() {
        return 'students';
    }

    public function getEmail() {
        return $this->getUser()->getKivutoEmail();
    }
}