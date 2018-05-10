<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class Builder {

    private $tokenStorage;
    private $decisionManager;
    private $factory;

    public function __construct(TokenStorageInterface $tokenStorage, AccessDecisionManagerInterface $decisionManager, FactoryInterface $factory) {
        $this->tokenStorage = $tokenStorage;
        $this->decisionManager = $decisionManager;
        $this->factory = $factory;
    }

    public function mainMenu(array $options) {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav nav-pills flex-column');

        $menu->addChild('menu.label', [
            'attributes' => [
                'class' => 'header'
            ]
        ]);

        $menu->addChild('dashboard.label', [
            'route' => 'dashboard'
        ]);

        if($this->decisionManager->decide($this->tokenStorage->getToken(), [ 'ROLE_ADMIN' ])) {
            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ]);
        }

        return $menu;
    }
}