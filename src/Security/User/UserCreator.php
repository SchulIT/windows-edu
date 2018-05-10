<?php

namespace App\Security\User;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use LightSaml\Model\Protocol\Response;
use LightSaml\SpBundle\Security\User\UserCreatorInterface;
use LightSaml\SpBundle\Security\User\UsernameMapperInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCreator implements UserCreatorInterface {
    /** @var ObjectManager */
    private $objectManager;

    /** @var UserMapper */
    private $userMapper;

    /** @var UsernameMapperInterface */
    private $usernameMapper;

    /**
     * @param ObjectManager $objectManager
     * @param UserMapper $userMapper
     * @param UsernameMapperInterface $usernameMapper
     */
    public function __construct(ObjectManager $objectManager, UserMapper $userMapper, UsernameMapperInterface $usernameMapper) {
        $this->objectManager = $objectManager;
        $this->userMapper = $userMapper;
        $this->usernameMapper = $usernameMapper;
    }

    /**
     * @param Response $response
     * @return UserInterface|null
     */
    public function createUser(Response $response) {
        $user = (new User())
            ->setUsername($this->usernameMapper->getUsername($response));

        $this->userMapper->mapUser($user, $response);
        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return $user;
    }


}