<?php
/*
 * @package todo
 * @copyright Copyright (c)2014 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

// Load FOF
include_once JPATH_LIBRARIES.'/f0f/include.php';
if(!defined('F0F_INCLUDED')) {
	JError::raiseError ('500', 'FOF is not installed');
	
	return;
}

F0FDispatcher::getTmpInstance('com_todo')->dispatch();
