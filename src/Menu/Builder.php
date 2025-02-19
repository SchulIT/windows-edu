<?php

namespace App\Menu;

use App\Entity\User;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use LightSaml\SpBundle\Security\Http\Authenticator\SamlToken;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class Builder {

    public function __construct(private FactoryInterface $factory,
                                private TokenStorageInterface $tokenStorage,
                                private AuthorizationCheckerInterface $authorizationChecker,
                                private TranslatorInterface $translator,
                                #[Autowire(env: 'IDP_PROFILE_URL')] private string $idpProfileUrl) {
    }

    public function mainMenu(array $options): ItemInterface {
        $user = $this->tokenStorage
            ->getToken()->getUser();

        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

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

            $menu->addChild('settings.branding.label', [
                'route' => 'branding_settings'
            ])
                ->setExtra('icon', 'fa-solid fa-cogs');

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

        if($this->tokenStorage->getToken() === null) {
            return $menu;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if(!$user instanceof User) {
            return $menu;
        }

        $displayName = $user->getUserIdentifier();

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
            ->setExtra('icon', 'fas fa-address-card');


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

        if($token instanceof SamlToken) {
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