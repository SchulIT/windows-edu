<?php

namespace App\Settings;

use Jbtronics\SettingsBundle\ParameterTypes\StringType;
use Jbtronics\SettingsBundle\Settings\Settings;
use Jbtronics\SettingsBundle\Settings\SettingsParameter;
use Jbtronics\SettingsBundle\Settings\SettingsTrait;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

#[Settings]
class BrandingSettings {
    use SettingsTrait;

    #[SettingsParameter(type: StringType::class, label: 'settings.branding.css.label', description: 'settings.branding.css.help', formType: TextareaType::class,  formOptions: [ 'attr' => [ 'rows' => 30, 'class' => 'font-monospace']], nullable: true)]
    public ?string $customCss = null;
}