<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 * 
 * This file is only loaded on Joomla! 3.x. This is a magic feature of FOF ;)
 * All you have to do is use the .j30.php extension. Cool, huh?
 */

defined('_JEXEC') or die();

// Load the CSS file
FOFTemplateUtils::addCSS('media://com_todo/css/backend.css');

// Load the Select helper class of our component
$this->loadHelper('select');

// Load the Javascript framework for Joomla!
JHTML::_('behavior.framework');

// Joomla! editor object
$editor = JFactory::getEditor();
?>
<form action="index.php" method="post" id="adminForm">
	<input type="hidden" name="option" value="com_todo" />
	<input type="hidden" name="view" value="item" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="todo_item_id" value="<?php echo $this->item->todo_item_id ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<div class="row-fluid">
		<div class="span6">
			<h3><?php echo JText::_('COM_TODO_ITEMS_GROUP_BASIC')?></h3>
			
			<div class="control-group">
				<label for="title" class="control-label">
					<?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE')?>
				</label>
				<div class="controls">
					<input type="text" name="title" id="title" value="<?php echo $this->item->title?>"/>
				</div>
			</div>
			<div class="control-group">
				<label for="due" class="control-label">
					<?php echo JText::_('COM_TODO_ITEMS_FIELD_DUE')?>
				</label>
				<div class="controls">
					<?php echo JHTML::_('calendar', $this->item->due, 'due', 'due'); ?>
				</div>
			</div>
		</div>
		<div class="span6">
			<h3><?php echo JText::_('COM_TODO_ITEMS_GROUP_DESCRIPTION')?></h3>

			<?php echo $editor->display( 'description',  $this->item->description, '100%', '350', '50', '10', false ) ; ?>
		</div>
	</div>
</form>