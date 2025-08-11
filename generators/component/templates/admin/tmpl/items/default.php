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
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

/** @var \Joomla\Component\<%= nsExtName %>\Administrator\View\<%= nsItemsName %>\HtmlView $this */

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('table.columns')
    ->useScript('multiselect');

$user      = $this->getCurrentUser();
$userId    = $user->id;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder && !empty($this->items)) {
    $saveOrderingUrl = 'index.php?option=com_<%= lExtName %> &task=<%= lItemsName %>.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
    HTMLHelper::_('draggablelist.draggable');
}
?>
<form action="<?php echo Route::_('index.php?option=com_<%= lExtName %> &view=<%= lItemsName %>'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <?php
                // Search tools bar
                echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]);
                ?>
                <?php if (empty($this->items)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                    </div>
                <?php else : ?>
                    <table class="table" id="<%= lItemName %>List">
                        <caption class="visually-hidden">
                            <?php echo Text::_('COM_<%= uExtName %>_<%= uItemsName %>_TABLE_CAPTION'); ?>,
                            <span id="orderedBy"><?php echo Text::_('JGLOBAL_SORTED_BY'); ?> </span>,
                            <span id="filteredBy"><?php echo Text::_('JGLOBAL_FILTERED_BY'); ?></span>
                        </caption>
                        <thead>
                            <tr>
                                <td class="w-1 text-center">
                                    <?php echo HTMLHelper::_('grid.checkall'); ?>
                                </td>
                                <th scope="col" class="w-1 text-center d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-sort'); ?>
                                </th>
                                <th scope="col" class="w-1 text-center">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                                </th>
                                <th scope="col">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'COM_<%= uExtName %>_HEADING_NAME', 'a.name', $listDirn, $listOrder); ?>
                                </th>
                                <?php if (Multilanguage::isEnabled()) : ?>
                                    <th scope="col" class="w-10 d-none d-md-table-cell">
                                        <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
                                    </th>
                                <?php endif; ?>
                                <th scope="col" class="w-5 d-none d-md-table-cell">
                                    <?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody <?php if ($saveOrder) :
                            ?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="true"<?php
                               endif; ?>>
                            <?php foreach ($this->items as $i => $item) :
                                $ordering  = ($listOrder == 'ordering');
                                $canCreate  = $user->authorise('core.create', 'com_<%= lExtName %> ');
                                $canEdit    = $user->authorise('core.edit', 'com_<%= lExtName %> ');
                                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || is_null($item->checked_out);
                                $canChange  = $user->authorise('core.edit.state', 'com_<%= lExtName %> ') && $canCheckin;
                                ?>
                                <tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo $item->catid; ?>">
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->name); ?>
                                    </td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <?php
                                        $iconClass = '';

                                        if (!$canChange) {
                                            $iconClass = ' inactive';
                                        } elseif (!$saveOrder) {
                                            $iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
                                        }
                                        ?>
                                        <span class="sortable-handler <?php echo $iconClass ?>">
                                            <span class="icon-ellipsis-v" aria-hidden="true"></span>
                                        </span>
                                        <?php if ($canChange && $saveOrder) : ?>
                                            <input type="text" name="order[]" size="5"
                                                value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo HTMLHelper::_('jgrid.published', $item->state, $i, '<%= lItemsName %>.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
                                    </td>
                                    <th scope="row">
                                        <div class="break-word">
                                            <?php if ($item->checked_out) : ?>
                                                <?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, '<%= lItemsName %>.', $canCheckin); ?>
                                            <?php endif; ?>
                                            <?php if ($canEdit) : ?>
                                                <a href="<?php echo Route::_('index.php?option=com_<%= lExtName %> &task=<%= lItemName %>.edit&id=' . (int) $item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->name); ?>">
                                                    <?php echo $this->escape($item->name); ?></a>
                                            <?php else : ?>
                                                <?php echo $this->escape($item->name); ?>
                                            <?php endif; ?>
                                            <div class="small break-word">
                                                <?php echo Text::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                            </div>
                                        </div>
                                    </th>
                                    <?php if (Multilanguage::isEnabled()) : ?>
                                        <td class="small d-none d-md-table-cell">
                                            <?php echo LayoutHelper::render('joomla.content.language', $item); ?>
                                        </td>
                                    <?php endif; ?>
                                    <td class="d-none d-md-table-cell">
                                        <?php echo $item->id; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php // Load the pagination. ?>
                    <?php echo $this->pagination->getListFooter(); ?>

                    <?php // Load the batch processing form. ?>
                    <?php
                    if (
                        $user->authorise('core.create', 'com_<%= lExtName %> ')
                        && $user->authorise('core.edit', 'com_<%= lExtName %> ')
                        && $user->authorise('core.edit.state', 'com_<%= lExtName %> ')
                    ) : ?>
                        <template id="joomla-dialog-batch"><?php echo $this->loadTemplate('batch_body'); ?></template>
                    <?php endif; ?>
                <?php endif; ?>

                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="0">
                <?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>
