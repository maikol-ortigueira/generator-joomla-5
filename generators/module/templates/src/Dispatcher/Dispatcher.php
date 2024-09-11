<?php

/**
 * @package     Joomla.Module
 * @subpackage  mod_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Module\<%= nsExtName %>\<%= nsModuleClient %>\Dispatcher;

use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

/**
 * Dispatcher class for mod_carta.
 * 
 * @since  5.1.3
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
    use HelperFactoryAwareTrait;

    /**
     * Returns the layout data.
     * 
     * @return  array  The layout data.
     * 
     * @since   5.1.3
     */
    protected function getLayoutData(): array
    {
        $data = parent::getLayoutData();

        $data['list'] = $this->getHelperFactory()->getHelper('<%= nsExtName %>Helper')->getNotificaciones($data['params'], $data['app']);

        return $data;
    }
}