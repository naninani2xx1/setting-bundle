<?php

namespace AmzsCMS\SettingBundle\Routing;

use AmzsCMS\CoreBundle\Constant\CoreConfig;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouteCollection;

class AmzsSettingLoader extends Loader
{
    private $isLoaded = false;
    private ParameterBagInterface $parameterBag;
    public function __construct(?string $env = null, ?ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($env);
        $this->parameterBag = $parameterBag;
    }

    public function load($resource, ?string $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "amzs_setting_route_loader" loader twice');
        }
        $type = 'yaml';
        $routes = new RouteCollection();

        /** @var $importedRoutes */
        $importedRoutes = $this->import($resource, $type);
        $routes->addCollection($importedRoutes);
        $routes->addPrefix($this->parameterBag->get(CoreConfig::PATH_TREE_BUILDER.'.routing.prefix_app'));

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, ?string $type = null): bool
    {
        return 'amzs_setting_route_loader' == $type;
    }
}