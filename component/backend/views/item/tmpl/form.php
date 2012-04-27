<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 */

defined('_JEXEC') or die();

// Load the CSS file
FOFTemplateUtils::addCSS('media://com_todo/css/backend.css');

// Load the Select helper class of our component
$this->loadHelper('select');

// Load the MooTools behaviour
JHTML::_('behavior.mootools');

// Joomla! editor object
$editor = JFactory::getEditor();
?>
<form action="index.php" method="post" name="adminForm">
	<input type="hidden" name="option" value="com_todo" />
	<input type="hidden" name="view" value="item" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="todo_item_id" value="<?php echo $this->item->todo_item_id ?>" />
	<input type="hidden" name="<?php echo JUtility::getToken();?>" value="1" />

	<fieldset id="subscriptions-basic">
		<legend><?php echo JText::_('COM_TODO_ITEMS_GROUP_BASIC')?></legend>
		
		<label for="title" class="todo-label todo-label-main"><?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE')?></label>
		<input type="text" name="title" id="title" value="<?php echo $this->item->title?>"/>
		<div class="todo-clear"></div>
		
		<label for="due" class="todo-label"><?php echo JText::_('COM_TODO_ITEMS_FIELD_DUE')?></label>
		<?php echo JHTML::_('calendar', $this->item->due, 'due', 'due'); ?>
		<div class="todo-clear"></div>
	</fieldset>
	
	<fieldset id="subscriptions-basic">
		<legend><?php echo JText::_('COM_TODO_ITEMS_GROUP_DESCRIPTION')?></legend>
		
		<?php echo $editor->display( 'description',  $this->item->description, '100%', '350', '50', '10', false ) ; ?>
	</fieldset>
</form>