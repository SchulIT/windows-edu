<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ShopVoter extends Voter {

    const string REDIRECT = 'shop.redirect';

    public function __construct(private readonly AccessDecisionManagerInterface $decisionManager) {
    }

    protected function supports($attribute, $subject): bool {
        return $attribute === static::REDIRECT;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool {
        if($token->getUser() === null) {
            return false;
        }

        return $this->decisionManager->decide($token, ['ROLE_STUDENT']);
    }
}