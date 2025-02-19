<?php

namespace App\Twig;

use App\Entity\User;
use Exception;
use LightSaml\SpBundle\Security\Http\Authenticator\SamlToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserVariable {
    public function __construct(private TokenStorageInterface $tokenStorage) { }

    private function getToken(): SamlToken  {
        $token = $this->tokenStorage->getToken();

        if(!$token instanceof SamlToken) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', SamlToken::class, get_class($token)));
        }

        return $token;
    }

    private function getUser(): User {
        $token = $this->getToken();
        $user = $token->getUser();

        if(!$user instanceof User) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', User::class, get_class($user)));
        }

        return $user;
    }

    public function getFirstname(): ?string {
        return $this->getUser()->getFirstname();
    }

    public function getLastname(): ?string {
        return $this->getUser()->getLastname();
    }

    public function getEmailAddress(): ?string {
        return $this->getUser()->getEmail();
    }

    public function getServices() {
        return $this->getToken()->getAttribute('services');
    }
}