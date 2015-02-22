<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license   GNU General Public License version 2 or later
 */

namespace Akeeba\Todo\Site\Model\Behaviour;

use FOF30\Event\Observer;
use FOF30\Model\DataModel;
use JDatabaseQuery;

defined('_JEXEC') or die;

/**
 * FOF model behavior class to apply ordering of items. This overrides any other ordering preference applied to the
 * model.
 *
 * @since    3.0
 */
class Ordered extends Observer
{
	/**
	 * This event runs before we build the query used to fetch a record
	 * list in a model. It is used to apply automatic query filters.
	 *
	 * @param   DataModel      &$model The model which calls this event
	 * @param   JDatabaseQuery &$query The query we are manipulating
	 *
	 * @return  void
	 */
	public function onBeforeBuildQuery(&$model, &$query)
	{
		// Make sure the field actually exists
		if (!$model->hasField('ordering'))
		{
			return;
		}

		$model->setState('filter_order', $model->getFieldAlias('ordering'));
		$model->setState('filter_order_Dir', 'ASC');
	}
}