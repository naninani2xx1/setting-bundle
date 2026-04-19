<?php

namespace AmzsCMS\SettingBundle\Twig\Extension;

use AmzsCMS\SettingBundle\Twig\Runtime\SettingRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('find_setting_by_multiple_keys', [SettingRuntime::class, 'findSettingByMultipleKeys']),
        ];
    }

    public function getFilters(): array
    {
        return [

        ];
    }
}
