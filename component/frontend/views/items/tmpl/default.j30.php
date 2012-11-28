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
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading', JText::_('COM_TODO_ITEMS_TITLE'))); ?>
</h1>
<?php endif; ?>

<div class="category-list">

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<div id="filter-bar" class="btn-toolbar">
		<div class="filter-search btn-group pull-left">
			<label for="filter_title" class="element-invisible"><?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE'); ?></label>
			<input type="text" name="title" placeholder="<?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE'); ?>" id="filter_title" value="<?php echo $this->escape($this->getModel()->getState('title','')); ?>" title="<?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE'); ?>" />
		</div>
		<div class="btn-group pull-left hidden-phone">
			<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
			<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_title').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
		</div>
		<div class="btn-group pull-right hidden-phone">
			<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
	</div>
	<div class="clearfix"> </div>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="list-title" id="tableOrdering">
					<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_TITLE', 'title', $this->lists->order_Dir, $this->lists->order) ?>
				</th>
				<th width="150">
					<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_DUE', 'due', $this->lists->order_Dir, $this->lists->order) ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php if($count = count($this->items)): ?>
		<?php $i = -1; $m = 1; ?>
		<?php foreach ($this->items as $item) : ?>
		<?php
			$i++; $m = 1-$m;
		?>
			<tr class="<?php echo 'row'.$m; ?>">
				<td align="left">
					<a href="index.php?option=com_todo&view=item&id=<?php echo $item->todo_item_id ?>" class="todoitem">
						<strong><?php echo $this->escape($item->title) ?></strong>
					</a>
				</td>
				<td>
					<?php echo TodoHelperFormat::dueDate($item->due); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="20">
					<?php echo  JText::_('COM_TODO_COMMON_NORECORDS') ?>
				</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>

	<?php echo $this->pagination->getListFooter(); ?>
</form>

</div>
