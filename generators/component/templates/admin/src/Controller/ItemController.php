<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Component\<%= nsExtName %>\Administrator\Controller;

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\MVC\Controller\FormController;

class <%= nsItemName %>Controller extends FormController
{
}