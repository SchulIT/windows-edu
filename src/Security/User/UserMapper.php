<?php

namespace App\Security\User;

use App\Entity\User;
use LightSaml\Model\Protocol\Response;

class UserMapper {
    const ROLES_ASSERTION_NAME = 'urn:roles';

    /**
     * @param User $user
     * @param Response $response
     * @return User
     */
    public function mapUser(User $user, Response $response) {
        $roles = $this->getValues($response, static::ROLES_ASSERTION_NAME);

        if(!is_array($roles)) {
            $roles = [ $roles ];
        }

        if(count($roles) === 0) {
            $roles = [ 'ROLE_USER' ];
        }

        $user->setRoles($roles);
        return $user;
    }

    private function getValues(Response $response, $attributeName) {
        return $response->getFirstAssertion()->getFirstAttributeStatement()
            ->getFirstAttributeByName($attributeName)->getAllAttributeValues();
    }
}