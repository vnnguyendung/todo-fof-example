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
// Construct the array of sorting fields
$sortFields = array(
	'title'		=> JText::_('COM_TODO_ITEMS_FIELD_TITLE'),
	'due'		=> JText::_('COM_TODO_ITEMS_FIELD_DUE'),
	'ordering'	=> JText::_('JFIELD_ORDERING_LABEL'),
	'enabled'	=> JText::_('JPUBLISHED'),
);

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$saveOrder	= $this->lists->order == 'ordering';
$hasAjaxOrderingSupport = $this->hasAjaxOrderingSupport();

?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->escape($this->lists->order); ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>


<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" id="option" value="com_todo" />
	<input type="hidden" name="view" id="view" value="items" />
	<input type="hidden" name="task" id="task" value="browse" />
	<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
	<input type="hidden" name="hidemainmenu" id="hidemainmenu" value="0" />
	<input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>" />
	<input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

<?php if (!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="title" class="element-invisible"><?php echo JText::_('COM_TODO_ITEMS_FIELD_TITLE'); ?></label>
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
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
					<option value="asc" <?php if ($this->lists->order_Dir == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
					<option value="desc" <?php if ($this->lists->order_Dir == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');  ?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $this->lists->order); ?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>
		
		<table class="adminlist table table-striped" id="itemsList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $this->lists->order_Dir, $this->lists->order, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th>
						<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_TITLE', 'title', $this->lists->order_Dir, $this->lists->order) ?>
					</th>
					<th width="12%" class="hidden-phone">
						<?php echo JHTML::_('grid.sort', 'COM_TODO_ITEMS_FIELD_DUE', 'due', $this->lists->order_Dir, $this->lists->order) ?>
					</th>
					<th width="8%">
						<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'enabled', $this->lists->order_Dir, $this->lists->order); ?>
					</th>
				</tr>
			</thead>

			<tbody>
			<?php if($count = count($this->items)): ?>
			<?php foreach ($this->items as $i => $item) : ?>
			<?php
				$ordering = $this->lists->order == 'ordering';
				$checkedOut = ($item->locked_by != 0);
			?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="order nowrap center hidden-phone">
					<?php if ($this->perms->editstate) :
						$disableClassName = '';
						$disabledLabel	  = '';

						if (!$hasAjaxOrderingSupport['saveOrder']) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>" rel="tooltip">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none"  name="order[]" size="5"
							value="<?php echo $item->ordering;?>" class="input-mini text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
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
					<td align="center">
						<?php echo JHtml::_('jgrid.published', $item->enabled, $i, '', $this->perms->editstate); ?>
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
	</div>
</form>