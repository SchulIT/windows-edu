<?php

namespace App\Security\User;

use App\Entity\User;
use LightSaml\ClaimTypes;
use LightSaml\Model\Protocol\Response;
use SchulIT\CommonBundle\Saml\ClaimTypes as SamlClaimTypes;
use SchulIT\CommonBundle\Security\User\AbstractUserMapper;

class UserMapper extends AbstractUserMapper {

    private $firstnameAttributeName;
    private $lastnameAttributeName;
    private $emailAttributeName;

    public function __construct(string $firstnameAttributeName, string $lastnameAttributeName, string $emailAttributeName) {
        $this->firstnameAttributeName = $firstnameAttributeName;
        $this->lastnameAttributeName = $lastnameAttributeName;
        $this->emailAttributeName = $emailAttributeName;
    }

    /**
     * @param User $user
     * @param Response|array[] $data Either a SAMLResponse or an array (keys: SAML Attribute names, values: corresponding values)
     * @return User
     */
    public function mapUser(User $user, $data) {
        if(is_array($data)) {
            return $this->mapUserFromArray($user, $data);
        } else if($data instanceof Response) {
            return $this->mapUserFromResponse($user, $data);
        }
    }

    private function mapUserFromResponse(User $user, Response $response) {
        return $this->mapUserFromArray($user, $this->transformResponseToArray(
            $response,
            [
                ClaimTypes::COMMON_NAME,
                SamlClaimTypes::ID,
                ClaimTypes::GIVEN_NAME,
                ClaimTypes::SURNAME,
                ClaimTypes::EMAIL_ADDRESS,
                SamlClaimTypes::EXTERNAL_ID,
                $this->firstnameAttributeName,
                $this->lastnameAttributeName,
                $this->emailAttributeName
            ],
            [
                static::ROLES_ASSERTION_NAME
            ]
        ));
    }

    /**
     * @param User $user User to populate data to
     * @param array<string, mixed> $data
     * @return User
     */
    private function mapUserFromArray(User $user, array $data) {
        $username = $data[ClaimTypes::COMMON_NAME];
        $firstname = $data[ClaimTypes::GIVEN_NAME];
        $lastname = $data[ClaimTypes::SURNAME];
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
            ->setRoles($roles)
            ->setKivutoFirstname($data[$this->firstnameAttributeName] ?? null)
            ->setKivutoLastname($data[$this->lastnameAttributeName] ?? null)
            ->setKivutoEmail($data[$this->emailAttributeName] ?? null);

        return $user;
    }
}