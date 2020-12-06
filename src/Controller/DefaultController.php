<?php

namespace App\Controller;

use App\Security\Voter\ShopVoter;
use SchulIT\KivutoBundle\Client\KivutoClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {
    /**
     * @Route("/", name="dashboard")
     * @Route("/start")
     */
    public function indexAction(Request $request) {
        return $this->render('index.html.twig', [
            'ip' => $request->getClientIp()
        ]);
    }

    /**
     * @Route("/redirect", name="redirect")
     */
    public function redirectAction(KivutoClientInterface $client) {
        $this->denyAccessUnlessGranted(ShopVoter::REDIRECT);

        $redirectUrl = $client->getRedirectUrl();
        return $this->redirect($redirectUrl, Response::HTTP_TEMPORARY_REDIRECT);
    }
}
