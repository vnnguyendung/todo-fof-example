<?php
/**
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class TodoToolbar extends FOFToolbar
{
    public function onItemsBrowse()
    {
        parent::onBrowse();

        JToolBarHelper::preferences('com_todo', 550, 875);
    }
}