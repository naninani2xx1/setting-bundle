<?php

namespace Amzs\SettingBundle\Twig\Runtime;

use Amzs\SettingBundle\Service\SettingService;
use Twig\Extension\RuntimeExtensionInterface;

class SettingRuntime implements RuntimeExtensionInterface
{
    private $settingService;
    public function __construct(
        SettingService $settingService
    )
    {
        $this->settingService = $settingService;
    }

    public function findSettingByMultipleKeys(array $keys)
    {
        return 'Hello';
    }
}