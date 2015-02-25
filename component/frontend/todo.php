<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

// Load FOF
if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
{
	throw new RuntimeException('FOF 3.0 is not installed', 500);
}

$container = FOF30\Container\Container::getInstance('com_todo')->dispatcher->dispatch();