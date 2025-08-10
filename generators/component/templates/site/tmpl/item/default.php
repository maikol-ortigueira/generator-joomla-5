<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

// phpcs: disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs: enable PSR1.Files.SideEffects

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use namespace <%= vendorName %>\Component\<%= nsExtName %>\Site\Helper\RouteHelper;

$tparams = $this->item->params;
$canDo   = ContentHelper::getActions('com_<%= lExtName %>', 'category', $this->item->catid);
$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by === $this->getCurrentUser()->id);
$htag    = $tparams->get('show_page_heading') ? 'h2' : 'h1';
$htag2   = ($tparams->get('show_page_heading') && $tparams->get('show_name')) ? 'h3' : 'h2';

?>

<div class="com-<%= lExtName %> <%= lItemName %>">
    <?php if ($tparams->get('show_page_heading')) : ?>
        <h1>
            <?php echo $this->escape($tparams->get('page_heading')); ?>
        </h1>
    <?php endif; ?>

    <?php if ($this->item->name && $tparams->get('show_name')) : ?>
        <div class="page-header">
            <<?php echo $htag; ?>>
                <?php if ($this->item->published == 0) : ?>
                    <span class="badge bg-warning text-light"><?php echo Text::_('JUNPUBLISHED'); ?></span>
                <?php endif; ?>
                <span class="<%= lItemName %>-name"><?php echo $this->item->name; ?></span>
            </<?php echo $htag; ?>>
        </div>
    <?php endif; ?>

    <?php if ($canEdit) : ?>
        <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
    <?php endif; ?>

    <?php $show_<%= lItemName %>_category = $tparams->get('show_<%= lItemName %>_category'); ?>

    <?php if ($show_<%= lItemName %>_category === 'show_no_link') : ?>
        <<?php echo $htag2; ?>>
            <span class="<%= lItemName %>-category"><?php echo $this->item->category_title; ?></span>
        </<?php echo $htag2; ?>>
    <?php elseif ($show_<%= lItemName %>_category === 'show_with_link') : ?>
        <?php $<%= lItemName %>Link = RouteHelper::getCategoryRoute($this->item->catid, $this->item->language); ?>
        <<?php echo $htag2; ?>>
            <span class="<%= lItemName %>-category"><a href="<?php echo $<%= lItemName %>Link; ?>">
                <?php echo $this->escape($this->item->category_title); ?></a>
            </span>
        </<?php echo $htag2; ?>>
    <?php endif; ?>
</div>
