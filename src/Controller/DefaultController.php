<?php

namespace App\Controller;

use App\Security\Voter\ShopVoter;
use SchoolIT\KivutoBundle\Client\KivutoClientInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    /**
     * @Route("/", name="dashboard")
     * @Route("/start")
     */
    public function indexAction(KivutoClientInterface $client) {
        $redirectUrl = null;

        if($this->isGranted(ShopVoter::REDIRECT)) {
            $redirectUrl = $client->getRedirectUrl();
        }

        return $this->render('index.html.twig', [
            'redirect_url' => $redirectUrl
        ]);
    }
}
