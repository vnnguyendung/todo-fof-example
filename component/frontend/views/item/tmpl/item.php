<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 */

defined('_JEXEC') or die();

// Load the CSS file
FOFTemplateUtils::addCSS('media://com_todo/css/frontend.css');

// Load the formatting class of our component
$this->loadHelper('format');
?>
<h1>
	<?php echo $this->escape($this->item->title); ?>
</h1>

<div class="todo-item-due">
	<?php echo JText::_('COM_TODO_ITEMS_FIELD_DUE') ?>:
	<?php echo TodoHelperFormat::dueDate($this->item->due); ?>
</div>

<fieldset class="todo-item-description">
	<legend><?php echo JText::_('COM_TODO_ITEMS_GROUP_DESCRIPTION') ?></legend>
	<?php echo $this->item->description; ?>
</fieldset>
