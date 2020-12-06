<?php

namespace App\Menu;

use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use LightSaml\SpBundle\Security\Authentication\Token\SamlSpToken;
use SchulIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use SchulIT\CommonBundle\Helper\DateHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Builder {

    private $factory;
    private $tokenStorage;
    private $authorizationChecker;
    private $dateHelper;
    private $translator;

    private $idpProfileUrl;

    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage,
                                AuthorizationCheckerInterface $authorizationChecker, DateHelper $dateHelper,
                                TranslatorInterface $translator, string $idpProfileUrl) {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->dateHelper = $dateHelper;
        $this->translator = $translator;
        $this->idpProfileUrl = $idpProfileUrl;
    }

    public function mainMenu(array $options): ItemInterface {
        $user = $this->tokenStorage
            ->getToken()->getUser();

        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav mr-auto');

        $menu->addChild('dashboard.label', [
            'route' => 'dashboard'
        ])
            ->setExtra('icon', 'fa fa-home');

        return $menu;
    }

    public function adminMenu(array $options): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu = $root->addChild('admin', [
                'label' => ''
            ])
                ->setExtra('icon', 'fa fa-cogs')
                ->setAttribute('title', $this->translator->trans('administration.label'))
                ->setExtra('menu', 'admin')
                ->setExtra('menu-container', '#submenu')
                ->setExtra('pull-right', true);

            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ])
                ->setExtra('icon', 'fas fa-clipboard-list');
        }


        return $root;
    }

    public function userMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $user = $this->tokenStorage->getToken()->getUser();

        if($user === null || !$user instanceof User) {
            return $menu;
        }

        $displayName = $user->getUsername();

        $userMenu = $menu->addChild('user', [
            'label' => $displayName
        ])
            ->setExtra('icon', 'fa fa-user')
            ->setExtra('menu', 'user')
            ->setExtra('menu-container', '#submenu')
            ->setExtra('pull-right', true);

        $userMenu->addChild('profile.label', [
            'uri' => $this->idpProfileUrl
        ])
            ->setLinkAttribute('target', '_blank')
            ->setExtra('icon', 'far fa-address-card');

        $menu->addChild('label.logout', [
            'route' => 'logout',
            'label' => ''
        ])
            ->setExtra('icon', 'fas fa-sign-out-alt')
            ->setAttribute('title', $this->translator->trans('auth.logout'));

        return $menu;
    }

    public function servicesMenu(): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $token = $this->tokenStorage->getToken();

        if($token instanceof SamlSpToken) {
            $menu = $root->addChild('services', [
                'label' => ''
            ])
                ->setExtra('icon', 'fa fa-th')
                ->setExtra('menu', 'services')
                ->setExtra('pull-right', true)
                ->setAttribute('title', $this->translator->trans('services.label'));

            foreach($token->getAttribute('services') as $service) {
                $item = $menu->addChild($service->name, [
                    'uri' => $service->url
                ])
                    ->setAttribute('title', $service->description)
                    ->setLinkAttribute('target', '_blank');

                if(isset($service->icon) && !empty($service->icon)) {
                    $item->setExtra('icon', $service->icon);
                }
            }
        }

        return $root;
    }
}