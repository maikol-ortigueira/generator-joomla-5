<?php

/**
 * @package     Joomla.Module
 * @subpackage  mod_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\HelperFactory;
use Joomla\CMS\Extension\Service\Provider\Module;
use Joomla\CMS\Extension\Service\Provider\ModuleDispatcherFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * The module <%= nsExtName %> HTML service provider.
 *
 * @since  4.4.0
 */
return new class () implements ServiceProviderInterface {
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   4.4.0
     */
    public function register(Container $container): void
    {
        $container->registerServiceProvider(new ModuleDispatcherFactory('\\<%= vendorName %>\\Module\\<%= nsExtName %>'));
        $container->registerServiceProvider(new HelperFactory('\\<%= vendorName %>\\Module\\<%= nsExtName %>\\Site\\Helper'));
        $container->registerServiceProvider(new Module());
    }
};
