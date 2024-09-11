<?php

/**
 * @package     Joomla.Module
 * @subpackage  mod_<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

\defined('_JEXEC') or die;

// vars to use are:
// $list: display the list of Foos
// $params: module params Ej: $paramName = $params->get('paramName', 'default value');
// $app: application object
?>

<div class="module-<%= lExtName %>">
    <ul>
        <?php foreach ($list as $item) : ?>
            <li><?php echo $item; ?></li>
        <?php endforeach; ?>
    </ul>
    <p>Your foo params: <strong><?php echo $params->get('foo', 'Hello world'); ?></strong></p>
</div>