<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

 namespace <%= vendorName %>\Component\<%= nsExtName %>\Site\Helper;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * <%= nsItemName %> Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @since       1.5
 */
abstract class RouteHelper
{
    /**
     * Get the URL route for a <%= lItemName %> from a <%= lItemName %> ID, <%= lItemName %> category ID and language
     *
     * @param   integer  $id        The id of the <%= lItemName %>
     * @param   integer  $catid     The id of the <%= lItemName %>'s category
     * @param   mixed    $language  The id of the language being used.
     *
     * @return  string  The link to the <%= lItemName %>
     *
     * @since   1.5
     */
    public static function get<%= nsItemName %>Route($id, $catid, $language = 0)
    {
        // Create the link
        $link = 'index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&id=' . $id;

        if ($catid > 1) {
            $link .= '&catid=' . $catid;
        }

        if ($language && $language !== '*' && Multilanguage::isEnabled()) {
            $link .= '&lang=' . $language;
        }

        return $link;
    }

    /**
     * Get the URL route for a <%= lItemName %> category from a <%= lItemName %> category ID and language
     *
     * @param   mixed  $catid     The id of the <%= lItemName %>'s category either an integer id or an instance of CategoryNode
     * @param   mixed  $language  The id of the language being used.
     *
     * @return  string  The link to the <%= lItemName %>
     *
     * @since   1.5
     */
    public static function getCategoryRoute($catid, $language = 0)
    {
        if ($catid instanceof CategoryNode) {
            $id = $catid->id;
        } else {
            $id       = (int) $catid;
        }

        if ($id < 1) {
            $link = '';
        } else {
            // Create the link
            $link = 'index.php?option=com_<%= lExtName %>&view=category&id=' . $id;

            if ($language && $language !== '*' && Multilanguage::isEnabled()) {
                $link .= '&lang=' . $language;
            }
        }

        return $link;
    }
}
