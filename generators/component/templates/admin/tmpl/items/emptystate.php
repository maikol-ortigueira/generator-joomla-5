<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;

$displayData = [
	'textPrefix' => 'COM_<%= uExtName %>_<%= uItemsName %>',
	'formURL'    => 'index.php?option=com_<%= lExtName %>&view=<%= lItemsName %>',
	'helpURL'	 => '',
	'icon'       => 'icon-copy <%= lItemsName %>',
];

if (count(Factory::getApplication()->getIdentity()->getAuthorisedViewLevels('com_<%= lExtName %>', 'core.create')) > 0)
{
	$displayData['createURL'] = 'index.php?option=com_<%= lExtName %>&task=<%= lItemName %>.add';
}

echo LayoutHelper::render('joomla.content.emptystate', $displayData);
