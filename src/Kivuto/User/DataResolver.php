<?php

namespace App\Kivuto\User;

use App\Entity\User;
use Exception;
use Faker\Generator;
use LightSaml\SpBundle\Security\Http\Authenticator\SamlToken;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class DataResolver implements DataResolverInterface {

    public function __construct(#[Autowire(env: 'KIVUTO_USERNAME_DOMAIN')] private string $usernameDomain,
                                private Generator $generator,
                                private TokenStorageInterface $tokenStorage) { }

    /**
     * @throws Exception
     */
    private function getToken(): SamlToken {
        $token = $this->tokenStorage->getToken();

        if(!$token instanceof SamlToken) {
            throw new Exception(sprintf('Token must be of type "%s" ("%s" given)', SamlSpToken::class, get_class($token)));
        }

        return $token;
    }

    /**
     * @throws Exception
     */
    private function getUser(): User {
        $token = $this->getToken();
        $user = $token->getUser();

        if(!$user instanceof User) {
            throw new Exception(sprintf('User must be of type "%s" ("%s" given)', User::class, get_class($user)));
        }

        return $user;
    }

    public function getUsername(): string {
        return sprintf('%s@%s', $this->getUser()->getIdpId()->toString(), $this->usernameDomain);
    }

    public function getFirstname(): string {
        return $this->getUser()->getFirstname();
    }

    public function getLastname(): string {
        return $this->getUser()->getLastname();
    }

    public function getAcademicStatus(): string {
        return 'students';
    }

    public function getEmail(): ?string {
        return $this->getUser()->getEmail();
    }
}