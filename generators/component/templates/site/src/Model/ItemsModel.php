<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Site\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

/**
 * <%= nsItemsName %> model for the Joomla <%= nsItemsName %> component.
 *
 * @since  1.6
 */
class <%= nsItemsName %>Model extends ListModel
{
    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');

        return parent::getStoreId($id);
    }

    /**
     * Method to get a DatabaseQuery object for retrieving the data set from a database.
     *
     * @return  DatabaseQuery   A DatabaseQuery object to retrieve the data set.
     *
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db         = $this->getDatabase();
        $query      = $db->getQuery(true);
        $ordering   = $this->getState('filter.ordering');
        $randomise  = ($ordering === 'random');
        $nowDate    = Factory::getDate()->toSql();

        $query->select(
            [
                $db->quoteName('a.id'),
                $db->quoteName('a.name'),
                $db->quoteName('a.params'),
            ]
        )
            ->from($db->quoteName('#__<%= lExtName %>_<%= lItemsName %>', 'a'))
            ->where($db->quoteName('a.state') . ' = 1')
            ->extendWhere(
                'AND',
                [
                    $db->quoteName('a.publish_up') . ' IS NULL',
                    $db->quoteName('a.publish_up') . ' <= :nowDate1',
                ],
                'OR'
            )
            ->extendWhere(
                'AND',
                [
                    $db->quoteName('a.publish_down') . ' IS NULL',
                    $db->quoteName('a.publish_down') . ' >= :nowDate2',
                ],
                'OR'
            )
            ->bind([':nowDate1', ':nowDate2'], $nowDate);


        // Filter by language
        // if ($this->getState('filter.language')) {
        //     $query->whereIn($db->quoteName('a.language'), [Factory::getLanguage()->getTag(), '*'], ParameterType::STRING);
        // }

        $query->order($db->quoteName('a.id') . ' DESC, ' . ($randomise ? $query->rand() : $db->quoteName('a.ordering')));

        return $query;
    }

    /**
     * Get a list of <%= lItemsName %>.
     *
     * @return  array
     *
     * @since   1.6
     */
    public function getItems()
    {
        if (!isset($this->cache['items'])) {
            $this->cache['items'] = parent::getItems();

            foreach ($this->cache['items'] as &$item) {
                $item->params = new Registry($item->params);
            }
        }

        return $this->cache['items'];
    }
}
