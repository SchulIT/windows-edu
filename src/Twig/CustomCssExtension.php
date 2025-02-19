<?php

namespace App\Twig;

use App\Settings\BrandingSettings;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CustomCssExtension extends AbstractExtension {
    public function __construct(private readonly BrandingSettings $brandingSettings) { }

    public function getFunctions(): array {
        return [
            new TwigFunction('customCSS', [$this, 'getCustomCSS'])
        ];
    }

    public function getCustomCSS(): string {
        return $this->brandingSettings->customCss;
    }
}