<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com (addition from FOF3 community)
 * @license   GNU General Public License version 2 or later
 */
 
 /**
 * ===DOCUMENTAION=== 
 * https://github.com/akeeba/fof/wiki/The-Model
 * https://github.com/akeeba/fof/wiki/The-DataModel
 * 
 * https://github.com/akeeba/fof/wiki/Using-Joomla%21-plugins-to-handle-FOF-events
 * 
 * Tip: You can see all the events called by a FOF class, e.g. FOF30\Model\DataModel,
 * by opening its code and searching for triggerEvent.
 * Remember to also check its parent class, e.g. FOF30\Model\Model,
 * in case some of the events are defined there.
 */

namespace Akeeba\Todo\Admin\Model;

class Item extends \FOF30\Model\DataModel
{
    // Model implementation goes here
    // i.e. getFieldnameAttribute, 
    
    // Simple example that ammends FOF's default slug creatoin
	protected function setSlugAttribute($value)
	{
		$value = str_replace('-','_',$value);//this should replace - with _.
	
		return $value;
	}
}
