<?php

namespace App\Security\User;

use App\Entity\User;
use Faker\Generator;
use LightSaml\ClaimTypes;
use LightSaml\Model\Protocol\Response;
use SchulIT\CommonBundle\Saml\ClaimTypes as SamlClaimTypes;
use SchulIT\CommonBundle\Security\User\AbstractUserMapper;

class UserMapper extends AbstractUserMapper {
    public function __construct(private readonly Generator $generator) { }

    /**
     * @param Response|array[] $data Either a SAMLResponse or an array (keys: SAML Attribute names, values: corresponding values)
     */
    public function mapUser(User $user, Response|array $data): User {
        if(is_array($data)) {
            return $this->mapUserFromArray($user, $data);
        } else if($data instanceof Response) {
            return $this->mapUserFromResponse($user, $data);
        }
    }

    private function mapUserFromResponse(User $user, Response $response): User {
        return $this->mapUserFromArray($user, $this->transformResponseToArray(
            $response,
            [
                ClaimTypes::COMMON_NAME,
                SamlClaimTypes::ID,
                ClaimTypes::GIVEN_NAME,
                ClaimTypes::SURNAME,
                ClaimTypes::EMAIL_ADDRESS,
                SamlClaimTypes::EXTERNAL_ID
            ],
            [
                static::ROLES_ASSERTION_NAME
            ]
        ));
    }

    /**
     * @param User $user User to populate data to
     * @param array<string, mixed> $data
     */
    private function mapUserFromArray(User $user, array $data): User {
        $username = $data[ClaimTypes::COMMON_NAME];
        $firstname = $data[ClaimTypes::GIVEN_NAME] ?? $this->generator->firstName();
        $lastname = $data[ClaimTypes::SURNAME] ?? $this->generator->lastName();
        $email = $data[ClaimTypes::EMAIL_ADDRESS];
        $roles = $data[static::ROLES_ASSERTION_NAME] ?? [ ];

        if(!is_array($roles)) {
            $roles = [ $roles ];
        }

        if(count($roles) === 0) {
            $roles = [ 'ROLE_USER' ];
        }

        $user
            ->setUsername($username)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setEmail($email)
            ->setRoles($roles);

        return $user;
    }
}