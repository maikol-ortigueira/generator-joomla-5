<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Site\Model;

use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Exception\CacheExceptionInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\ParameterType;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

/**
 * <%= nsItemName %> Site Model
 *
 * @since  1.5
 */
class <%= nsItemName %>Model extends BaseDatabaseModel
{
    /**
    * Cached item object
    *
    * @var    object
    * @since  1.6
    */
   protected $_item;


    /**
     * Get the data for a <%= lItemName %>.
     *
     * @return  object
     *
     * @since   1.6
     */
    public function &getItem()
    {
        if (!isset($this->_item)) {
            /** @var \Joomla\CMS\Cache\Controller\CallbackController $cache */
            $cache = Factory::getContainer()->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', ['defaultgroup' => 'com_<%= lExtName %>']);

            $id = (int) $this->getState('<%= lItemName %>.id');

            // For PHP 5.3 compat we can't use $this in the lambda function below, so grab the database driver now to use it
            $db = $this->getDatabase();

            $loader = function ($id) use ($db) {
                $query = $db->getQuery(true);

                $query->select(
                    [
                        $db->quoteName('a.name'),
                        $db->quoteName('a.alias'),
                        $db->quoteName('a.params'),
                    ]
                )
                    ->from($db->quoteName('#__<%= lExtName %>_<%= lItemsName %>', 'a'))
                    ->where($db->quoteName('a.id') . ' = :id')
                    ->bind(':id', $id, ParameterType::INTEGER);

                $db->setQuery($query);

                return $db->loadObject();
            };

            try {
                $this->_item = $cache->get($loader, [$id], md5(__METHOD__ . $id));
            } catch (CacheExceptionInterface $e) {
                $this->_item = $loader($id);
            }
        }

        return $this->_item;
    }
}
