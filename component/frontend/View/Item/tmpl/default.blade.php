{{--
@package	todo
@copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd
@license	GNU GPL version 3 or later
--}}
<?php
/** @var \FOF30\View\DataView\Html $this */

$routeToSelf = \JRoute::_('index.php?option=com_todo&view=items');
$uniqueId = md5(microtime() . rand(0, 1000000));

$this->getContainer()->template->addCSS('media://com_todo/css/frontend.css');
?>

@section('header')
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="<?php echo $routeToSelf ?>">
				@lang('COM_TODO_ITEMS_TITLE')
			</a>
			<form class="navbar-form pull-left" name="comTodoHeaderInlineForm{{$uniqueId}}"
				  action="<?php echo $routeToSelf ?>" method="POST">
				<input type="text"
					   placeholder="@lang('COM_TODO_ITEMS_FIELD_TITLE')"
					   name="title" id="comTodoFilterTitle{{$uniqueId}}"
					   value="<?php echo $this->getModel()->getState('title', '', 'string') ?>" />
				<button onclick="document.forms.comTodoHeaderInlineForm{{$uniqueId}}.submit();"
						class="btn btn-small">
					<span class="icon icon-search"></span>
				</button>
				<button
						onclick="document.getElementById('comTodoFilterTitle{{$uniqueId}}').value = ''; document.forms.comTodoHeaderInlineForm{{$uniqueId}}.submit();"
						class="btn btn-small">
					<span class="icon icon-cancel-2"></span>
				</button>
			</form>
			<div class="pull-right">
				<a href="@route('index.php?option=com_todo&view=item&task=add')" class="btn btn-success">
					<span class="icon icon-new"></span>
					@lang('JTOOLBAR_NEW')
				</a>
			</div>
		</div>
	</div>
@show

@section('items')
	<table class="table table-striped">
	<thead>
		<tr>
			<th>
				Title
			</th>
			<th width="30px">
				&nbsp;
			</th>
			<th width="20%">
				Due Date
			</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($this->items as $item)
		<tr>
			<td>
				<a href="@route('index.php?option=com_todo&view=item&task=read&id=' . $item->todo_item_id)">
				{{{$item->title}}}
				</a>
			</td>
			<td>
				<a class="btn btn-small" href="@route('index.php?option=com_todo&view=item&task=edit&id=' . $item->todo_item_id)">
					<span class="icon icon-edit"></span>
				</a>
			</td>
			<td>
				@date($item->due)
			</td>
		</tr>
	@endforeach
	</tbody>
	</table>
@show

{{$this->getPagination()->getPaginationLinks()}}