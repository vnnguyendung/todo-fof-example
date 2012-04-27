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
<div class="category-list">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading', JText::_('COM_TODO_ITEMS_TITLE'))); ?>
	</h1>
	<?php endif; ?>

<form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset class="filters">
		<legend class="hidelabeltxt">
			<?php echo JText::_('JGLOBAL_FILTER_LABEL'); ?>
		</legend>

		<div class="filter-search">
			<label class="filter-search-lbl" for="title"><?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE').'&#160;'; ?></label>
			<input type="text" name="title" id="title" value="<?php echo $this->escape($this->getModel()->getState('title',''));?>" class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			<button onclick="this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_SUBMIT') ?>
			</button>
			<button onclick="document.adminForm.title.value='';this.form.submit();">
				<?php echo JText::_('JSEARCH_FILTER_CLEAR') ?>
			</button>
		</div>
		
		<div class="display-limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
			<?php echo $this->pagination->getLimitBox(); ?>
		</div>
		
		<input type="hidden" name="filter_order" value="" />
		<input type="hidden" name="filter_order_Dir" value="" />
		<input type="hidden" name="limitstart" value="" />
	</fieldset>
	
	<table class="category">
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

<?php if (!empty($this->items)) : ?>
	<div class="pagination">
		<p class="counter">
			<?php echo $this->pagination->getPagesCounter(); ?>
		</p>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php  endif; ?>
</form>

</div>
