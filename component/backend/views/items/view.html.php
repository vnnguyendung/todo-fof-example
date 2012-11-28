<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 3 or later
 */

defined('_JEXEC') or die();

class TodoViewItems extends FOFViewHtml
{
	protected function onBrowse($tpl = null) {
		$result = parent::onBrowse($tpl);
		if($result && version_compare(JVERSION, '3.0', 'ge')) {
			// Set up the sidebar filters
			$this->loadHelper('select');
			JHtmlSidebar::setAction('index.php?option=com_todo&view=items');
			JHtmlSidebar::addFilter(
				JText::_('COM_TODO_SELECTSTATE'),
				'enabled',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash' => 0, 'all' => 0)), 'value', 'text', $this->getModel()->getState('enabled',''), true)
			);
			
			$this->sidebar = JHtmlSidebar::render();
		}
		return $result;
	}
}