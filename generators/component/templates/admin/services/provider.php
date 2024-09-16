<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\Registry;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use <%= vendorName %>\Component\<%= nsExtName %>\Administrator\Extension\<%= nsExtName %>Component;

return new class implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new CategoryFactory('\\<%= vendorName %>\\Component\\<%= nsExtName %>'));
        $container->registerServiceProvider(new MVCFactory('\\<%= vendorName %>\\Component\\<%= nsExtName %>'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\<%= vendorName %>\\Component\\<%= nsExtName %>'));
        $container->registerServiceProvider(new RouterFactory('\\<%= vendorName %>\\Component\\<%= nsExtName %>'));
        
        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new <%= nsExtName %>Component($container->get(ComponentDispatcherFactoryInterface::class));
                
                $component->setRegistry($container->get(Registry::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setCategoryFactory($container->get(CategoryFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            }
        );
    }
};
