<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 */

defined('_JEXEC') or die();

class TodoToolbar extends FOFToolbar
{
	public function onItemsBrowse()
	{
		parent::onBrowse();
		
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_todo');
	}
}