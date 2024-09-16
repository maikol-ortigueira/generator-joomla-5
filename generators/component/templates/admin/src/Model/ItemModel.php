<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Administrator\Model;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\MVC\Model\AdminModel;

class <%= nsItemName %>Model extends AdminModel
{    
    /**
    * The prefix to use with controller messages.
    *
    * @var    string
    * @since  1.6
    */
   protected $text_prefix = 'COM_<%= uExtName %>_<%= uItemName %>';

   /**
    * The type alias for this content type.
    *
    * @var    string
    * @since  3.2
    */
   public $typeAlias = 'com_<%= lExtName %>.<%= lItemName %>';


    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form. [optional]
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
     *
     * @return  Form|boolean  A Form object on success, false on failure
     */
    public function getForm(array $data = array(), bool $loadData = true): Form|bool
    {
        $form = $this->loadForm('com_<%= lExtName %>.<%= lItemName %>', '<%= lItemName %>', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     */
    protected function loadFormData(): mixed
    {
        /** @var CMSApplication $app */
        $app = Factory::getApplication();

        // Comprueba la sesión para datos previamente introducidos.
        $data = $app->getUserState('com_<%= lExtName %>.edit.<%= lItemName %>.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_<%= lExtName %>.<%= lItemName %>', $data);
        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     */
    public function getItem(int $pk = null): mixed
    {
        return parent::getItem($pk);
    }
}