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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var \<%= vendorName %>\Component\<%= nsExtName %>\Administrator\View\<%= nsItemName %>\HtmlView $this */

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('multiselect')
	->useScript('form.validate');

?>

<form
    action="<?php echo Route::_('index.php?option=com_<%= lExtName %>&view=<%= lItemName %>&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post"
    name="adminForm"
    id="<%= lItemName %>-form"
    aria-label="<?php echo Text::_('COM_<%= uExtName %>_<%= uItemName %>_FORM_' . ((int) $this->item->id === 0 ? 'NEW' : 'EDIT'), true); ?>"
    class="form-validate form-horizontal"
    >

	<?php echo LayoutHelper::render('joomla.edit.title_alias', $this); 
	?>

	<div class="main-card">
		<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'basic', 'recall' => true, 'breakpoint' => 768]); ?>

		<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'datos', Text::_('###########################')); ?>
		<div class="row">
			<div class="col-lg-9">
				<fieldset class="options-form">
					<legend><?php echo Text::_('###########################'); ?></legend>
				</fieldset>
			</div>
			<div class="col-lg-3">
				<fieldset id="fieldset-publish" class="options-form">
					<legend><?php echo Text::_('###########################'); ?></legend>
					<?php echo LayoutHelper::render('ortiga.edit.publishingdata', $this); ?>
				</fieldset>
			</div>
		</div>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>

		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token', ['id' => 'token']); ?>
</form>