<?php

/**
 * @package     Joomla.Module
 * @subpackage  mod_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Module\<%= nsExtName %>\<%= nsModuleClient %>\Helper;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

/**
 * Helper class for mod_<%= lExtName %>.
 * 
 * @since  5.1.3
 */
class <%= nsExtName %>Helper
{
    /**
     * Returns the Foos.
     * 
     * @param   object  $params  The module params.
     * @param   object  $app     The application.
     * 
     * @return  array  The Foos.
     * 
     * @since   5.1.3
     */
    public function getFoos(Registry $params, SiteApplication $app): array
    {
        
        // Fetch the list of Foos.
        $list = [];

        // Add your code here.

        // Return the list of Foos.
        return $list;
    }

    public static function getList(Registry &$params)
    {
        /** @var SiteApplication $app */
        $app = Factory::getApplication();

        return (new self())->getFoos($params, $app);
    }
}
