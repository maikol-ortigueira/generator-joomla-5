<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Administrator\Table;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Versioning\VersionableTableInterface;
use Joomla\Database\DatabaseDriver;
use Joomla\Event\DispatcherInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * <%= nsItemName %> table
 *
 * @since  1.6
 */
class <%= nsItemName %>Table extends Table implements VersionableTableInterface
{
    /**
     * Indicates that columns fully support the NULL value in the database
     *
     * @var    boolean
     * @since  4.0.0
     */
    protected $_supportNullValue = true;

    /**
     * Constructor
     *
     * @param   DatabaseDriver        $db          Database connector object
     * @param   ?DispatcherInterface  $dispatcher  Event dispatcher for this table
     *
     * @since   1.5
     */
    public function __construct(DatabaseDriver $db, DispatcherInterface $dispatcher = null)
    {
        $this->typeAlias = 'com_<%= lExtName %>.<%= lItemName %>';

        parent::__construct('#__<%= lExtName %>_<%= lItemsName %>', 'id', $db, $dispatcher);
        $this->created = Factory::getDate()->toSql();
        $this->setColumnAlias('published', 'state');
        $this->setColumnAlias('title', 'name');
    }

    /**
     * Get the type alias for the history table
     *
     * @return  string  The alias as described above
     *
     * @since   4.0.0
     */
    public function getTypeAlias()
    {
        return $this->typeAlias;
    }

    /**
     * Overloaded check function
     *
     * @return  boolean  True if the object is ok
     *
     * @see     Table::check()
     * @since   4.0.0
     */
    public function check()
    {
        try {
            parent::check();
        } catch (\Exception $e) {
            $this->setError($e->getMessage());

            return false;
        }

        // Check for valid name
        if (trim($this->name) === '') {
            $this->setError(Text::_('COM_<%= uExtName %>_<%= uItemName %>_WARNING_PROVIDE_VALID_NAME'));

            return false;
        }

        // Set name
        $this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

        // Set alias
        if (trim($this->alias) === '') {
            $this->alias = $this->name;
        }

        $this->alias = ApplicationHelper::stringURLSafe($this->alias);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
        }

        // Set created date if not set.
        if (!(int) $this->created) {
            $this->created = Factory::getDate()->toSql();
        }

        // Set publish_up, publish_down to null if not set
        if (!$this->publish_up) {
            $this->publish_up = null;
        }

        if (!$this->publish_down) {
            $this->publish_down = null;
        }

        // Check the publish down date is not earlier than publish up.
        if (!\is_null($this->publish_down) && !\is_null($this->publish_up) && $this->publish_down < $this->publish_up) {
            $this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

            return false;
        }

        // Set ordering
        if ($this->state < 0) {
            // Set ordering to 0 if state is archived or trashed
            $this->ordering = 0;
        } elseif (empty($this->ordering)) {
            // Set ordering to last if ordering was 0
            $this->ordering = $this->getNextOrder($this->_db->quoteName('state') . ' >= 0');
        }

        // Set modified to created if not set
        if (!$this->modified) {
            $this->modified = $this->created;
        }

        // Set modified_by to created_by if not set
        if (empty($this->modified_by)) {
            $this->modified_by = $this->created_by;
        }

        if (is_null($this->params)) {
            $this->params = "";
        } elseif (!is_object($this->params)) {
            $this->params = json_decode($this->params);
        }

        return true;
    }
}
