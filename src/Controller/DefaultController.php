<?php

namespace App\Controller;

use App\Kivuto\Client\KivutoClientInterface;
use App\Security\Voter\ShopVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController {
    #[Route('/')]
    #[Route('/dashboard', name: 'dashboard')]
    public function indexAction(Request $request): Response {
        return $this->render('index.html.twig', [
            'ip' => $request->getClientIp()
        ]);
    }

    #[Route('/redirect', name: 'redirect')]
    public function redirectAction(KivutoClientInterface $client): RedirectResponse {
        $this->denyAccessUnlessGranted(ShopVoter::REDIRECT);

        $redirectUrl = $client->getRedirectUrl();
        return $this->redirect($redirectUrl, Response::HTTP_TEMPORARY_REDIRECT);
    }
}
