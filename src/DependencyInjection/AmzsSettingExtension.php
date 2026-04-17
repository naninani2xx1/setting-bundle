<?php

namespace AmzsCMSSettingBundle\DependencyInjection;

use AmzsCMSSettingBundle\Constant\SettingRoute;
use AmzsCMSSettingBundle\Constant\SettingType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AmzsSettingExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');

//        $container->setParameter('amzs.setting_bundle.default_password', $config['default_password']);
    }

    public function prepend(ContainerBuilder $container)
    {
        // add constants
        $container->prependExtensionConfig('twig', [
            'globals' => [
                'setting_type_image' => SettingType::SETTING_TYPE_IMAGE,
                'setting_type_json' => SettingType::SETTING_TYPE_JSON,
                'setting_type_key_value' => SettingType::SETTING_TYPE_KEY_VALUE,
            ],
        ]);

        // add route into twig
        $container->prependExtensionConfig('twig', [
            'globals' => [
                'amzs_setting_index_route' => SettingRoute::ROUTE_INDEX,
                'amzs_setting_edit_route' => SettingRoute::ROUTE_EDIT,
                'amzs_setting_add_route' => SettingRoute::ROUTE_ADD,
                'amzs_setting_data_route' => SettingRoute::ROUTE_DATA,
            ],
        ]);
    }
}