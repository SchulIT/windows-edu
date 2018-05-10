<?php

namespace App\Security\User;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SchoolIT\CommonBundle\Security\AuthenticationEvent;
use SchoolIT\CommonBundle\Security\SecurityEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserUpdater implements EventSubscriberInterface {

    /** @var UserMapper  */
    private $userMapper;

    /** @var ObjectManager  */
    private $objectManager;

    /** @var LoggerInterface|NullLogger  */
    private $logger;

    public function __construct(ObjectManager $objectManager, UserMapper $userMapper, LoggerInterface $logger = null) {
        $this->userMapper = $userMapper;
        $this->objectManager = $objectManager;
        $this->logger = $logger ?? new NullLogger();
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event) {
        $token = $event->getToken();
        $user = $event->getUser();

        if($token === null) {
            $this->logger
                ->debug('Token is null, cannot update user');
            return;
        }

        if($user === null) {
            $this->logger
                ->debug('User is null, cannot update user');
            return;
        }

        if(!$user instanceof User) {
            $this->logger
                ->debug(sprintf('User is not of type "%s" ("%s" given), cannot update user', User::class, get_class($user)));
        }

        $response = $token->getResponse();

        $user = $this->userMapper->mapUser($user, $response);
        $this->objectManager->persist($user);
        $this->objectManager->flush();

        $this->logger
            ->debug('User updated from SAML response');
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() {
        return [
            SecurityEvents::SAML_AUTHENTICATION_SUCCESS => [
                [ 'onAuthenticationSuccess', 10 ]
            ]
        ];
    }
}