<?php

namespace App\Controller;

use App\Settings\BrandingSettings;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/settings')]
#[IsGranted('ROLE_ADMIN')]
class SettingsController extends AbstractController {

    public function __construct(private readonly SettingsManagerInterface $settingsManager, private readonly SettingsFormFactoryInterface $formFactory) {

    }

    #[Route('/branding', name: 'branding_settings')]
    public function branding(Request $request): RedirectResponse|Response {
        $clone = $this->settingsManager->createTemporaryCopy(BrandingSettings::class);
        $builder = $this->formFactory->createSettingsFormBuilder($clone);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->settingsManager->mergeTemporaryCopy($clone);
            $this->settingsManager->save();

            $this->addFlash('success', 'settings.success');
            return $this->redirectToRoute('branding_settings');
        }

        return $this->render('settings/branding.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}