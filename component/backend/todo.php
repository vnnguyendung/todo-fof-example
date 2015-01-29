<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

// Load FOF
include_once JPATH_LIBRARIES . '/fof3/fof30/include.php';

if (!defined('FOF30_INCLUDED'))
{
	throw new RuntimeException('500', 'FOF 3.0 is not installed');
}

\FOF30\Container\Container::getInstance('com_todo', array(
	'factoryClass' => 'FOF30\\Factory\\MagicSwitchFactory'
))->dispatcher->dispatch();