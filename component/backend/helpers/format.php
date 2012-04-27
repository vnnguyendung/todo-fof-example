<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 */

defined('_JEXEC') or die();

class TodoHelperFormat
{
	public static function dueDate($dueOn)
	{
		jimport('joomla.utilities.date');
		$jDue = new JDate($dueOn);
		$jNow = new JDate();
		
		$daysLeft = $jDue->toUnix() - $jNow->toUnix();
		if($daysLeft < 0) {
			$class = 'todo-due-overdue';
		} elseif($daysLeft < 7) {
			$class = 'todo-due-closing';
		} else {
			$class = 'todo-due-enoughtime';
		}

		$html = '<span class="'.$class.'">'.$jDue->format(JText::_('DATE_FORMAT_LC'), true, true).'</span>';
		return $html;
	}
}