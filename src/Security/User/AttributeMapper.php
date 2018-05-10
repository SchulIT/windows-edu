<?php

namespace App\Security\User;

use LightSaml\ClaimTypes;
use LightSaml\SpBundle\Security\Authentication\Token\SamlSpResponseToken;
use LightSaml\SpBundle\Security\User\AttributeMapperInterface;

class AttributeMapper implements AttributeMapperInterface {

    const INTERAL_ID_ASSERTION_NAME = 'urn:internal-id';

    public function getAttributes(SamlSpResponseToken $token) {
        return [
            'name_id' => $token->getResponse()->getFirstAssertion()->getSubject()->getNameID()->getValue(),
            'email' => $this->getValue($token, ClaimTypes::EMAIL_ADDRESS),
            'student_id' => $this->getValue($token, static::INTERAL_ID_ASSERTION_NAME),
            'firstname' => $this->getValue($token, ClaimTypes::GIVEN_NAME),
            'lastname' => $this->getValue($token, ClaimTypes::SURNAME),
            'services' => $this->getServices($token)
        ];
    }

    private function getServices(SamlSpResponseToken $token) {
        $values = $this->getValues($token, 'urn:services');

        $services = [ ];

        foreach($values as $value) {
            $services[] = json_decode($value);
        }

        return $services;
    }

    private function getValue(SamlSpResponseToken $token, $attributeName) {
        return $token->getResponse()->getFirstAssertion()->getFirstAttributeStatement()
            ->getFirstAttributeByName($attributeName)->getFirstAttributeValue();
    }

    private function getValues(SamlSpResponseToken $token, $attributeName) {
        return $token->getResponse()->getFirstAssertion()->getFirstAttributeStatement()
            ->getFirstAttributeByName($attributeName)->getAllAttributeValues();
    }
}