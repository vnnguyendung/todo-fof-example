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
// Load the formatting class of our component
$this->loadHelper('format');
?>
<form name="adminForm" id="adminForm" action="index.php" method="post">
	<input type="hidden" name="option" id="option" value="com_todo" />
	<input type="hidden" name="view" id="view" value="items" />
	<input type="hidden" name="task" id="task" value="browse" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" id="hidemainmenu" value="0" />
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
	<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />
	<input type="hidden" name="<?php echo JUtility::getToken();?>" value="1" />

<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ) + 1; ?>);" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_TITLE', 'title', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="12%">
				<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_DUE', 'due', $this->lists->order_Dir, $this->lists->order) ?>
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort', 'JFIELD_ORDERING_LABEL', 'ordering', $this->lists->order_Dir, $this->lists->order); ?>
				<?php echo JHTML::_('grid.order', $this->items); ?>
			</th>
			<th width="8%">
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'enabled', $this->lists->order_Dir, $this->lists->order); ?>
			</th>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="text" name="title" id="title"
					value="<?php echo $this->escape($this->getModel()->getState('title',''));?>"
					class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();">
					<?php echo JText::_('JSEARCH_FILTER') ?>
				</button>
				<button onclick="document.adminForm.title.value='';this.form.submit();">
					<?php echo JText::_('JSEARCH_RESET') ?>
				</button>
			</td>
			<td></td>
			<td></td>
			<td>
				<?php echo TodoHelperSelect::published($this->getModel()->getState('enabled',''), 'enabled', array('onchange'=>'this.form.submit();')) ?>
			</td>
		</tr>
	</thead>
	
	<tfoot>
		<tr>
			<td colspan="20">
				<?php if($this->pagination->total > 0) echo $this->pagination->getListFooter() ?>	
			</td>
		</tr>
	</tfoot>
	
	<tbody>
	<?php if($count = count($this->items)): ?>
	<?php $i = -1; $m = 1; ?>
	<?php foreach ($this->items as $item) : ?>
	<?php
		$i++; $m = 1-$m;
		$checkedOut = ($item->locked_by != 0);
		$ordering = $this->lists->order == 'ordering';
		$item->published = $item->enabled;
	?>
		<tr class="<?php echo 'row'.$m; ?>">
			<td>
				<?php echo JHTML::_('grid.id', $i, $item->todo_item_id, $checkedOut); ?>
			</td>
			<td align="left">
				<span class="editlinktip hasTip" title="<?php echo JText::_('COM_TODO_ITEM_EDIT_TOOLTIP')?> <?php echo $this->escape($item->title); ?>::<?php echo $this->escape(substr(strip_tags($item->description), 0, 300)).'...'; ?>">
					<a href="index.php?option=com_todo&view=item&id=<?php echo $item->todo_item_id ?>" class="todoitem">
						<strong><?php echo $this->escape($item->title) ?></strong>
					</a>
				</span>
			</td>
			<td>
				<?php echo TodoHelperFormat::dueDate($item->due); ?>
			</td>
			<td class="order" align="center">
				<span><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $ordering ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $count, true, 'orderdown', 'Move Down', $ordering ); ?></span>
				<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text_area" style="text-align: center" />
			</td>
			<td align="center">
				<?php echo JHTML::_('grid.published', $item, $i); ?>
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