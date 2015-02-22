<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU General Public License version 2 or later
 */

namespace Akeeba\Todo\Admin\Form\Field;

use FOF30\Form\Field\Calendar;
use FOF30\Form\FieldInterface;
use DateTimeZone;
use JDate;
use JFactory;
use JText;

class DueDate extends Calendar
{
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 *
	 * @since 2.0
	 */
	public function getRepeatable($options = array())
	{
		// Initialize some field attributes.
		$format = $this->element['format'] ? (string)$this->element['format'] : '%Y-%m-%d';

		$class = $this->element['class'] ? ' class="' . (string)$this->element['class'] . '"' : '';

		// Get some system objects.
		$config = JFactory::getConfig();
		$user = JFactory::getUser();

		// If a known filter is given use it.
		switch (strtoupper((string)$this->element['filter']))
		{
			case 'SERVER_UTC':
				// Convert a date to UTC based on the server timezone.
				if ((int)$this->value)
				{
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone(new DateTimeZone($config->get('offset')));

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}
				break;

			case 'USER_UTC':
				// Convert a date to UTC based on the user timezone.
				if ((int)$this->value)
				{
					// Get a date object based on the correct timezone.
					$date = JFactory::getDate($this->value, 'UTC');
					$date->setTimezone(new DateTimeZone($user->getParam('timezone', $config->get('offset'))));

					// Transform the date string.
					$this->value = $date->format('Y-m-d H:i:s', true, false);
				}
				break;
		}

		jimport('joomla.utilities.date');
		$jDue = new JDate($this->value);
		$jNow = new JDate();

		$daysLeft = $jDue->toUnix() - $jNow->toUnix();
		if ($daysLeft < 0)
		{
			$class = 'todo-due-overdue';
		}
		elseif ($daysLeft < 7)
		{
			$class = 'todo-due-closing';
		}
		else
		{
			$class = 'todo-due-enoughtime';
		}

		$html = '<span class="' . $class . '">' . $jDue->format(JText::_('DATE_FORMAT_LC'), true, true) . '</span>';

		return $html;
	}
}