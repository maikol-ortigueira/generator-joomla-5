<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Administrator\Extension;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Fields\FieldsServiceInterface;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\Component\Categories\Administrator\Service\HTML\AdministratorService;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_<%= lExtName %>
 *
 * @since  4.0.0
 */
class OrtentrenamientosComponent extends MVCComponent implements
    BootableExtensionInterface,
    RouterServiceInterface,
    CategoryServiceInterface,
    FieldsServiceInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;
    use CategoryServiceTrait;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * If required, some initial set up can be done from services of the container, eg.
     * registering HTML services.
     *
     * @param   ContainerInterface  $container  The container
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function boot(ContainerInterface $container)
    {
        $this->getRegistry()->register('<%= lExtName %>administrator', new AdministratorService);
    }
}
