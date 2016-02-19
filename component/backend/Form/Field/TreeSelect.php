<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com (addition from FOF3 community)
 * @license   GNU General Public License version 2 or later
 */

namespace Akeeba\Todo\Admin\Form\Field;

use FOF30\Container\Container;
use FOF30\Form\FieldInterface;
use FOF30\Form\Form;
use FOF30\Form\Field\GenericList;
use FOF30\Model\DataModel;
use FOF30\Utils\StringHelper;
use \JHtml;
use \JText;

defined('_JEXEC') or die;

\JFormHelper::loadFieldClass('list');

/**
 * Form Field class for FOF
 * Generic list from a model's results
 */
 
// Basic & butchered version of FOF30\Form\Field\Model field to allow single item selection from the Tree
// with some js this could set hidden `lft` & `rgt` fields
// alternatively hidden static `lft`=2 & `rgt`=3 fields could be updated in the Model

/* Attributes-

`model` (same as FOF30\Form\Field\Model)
`key_field` (same as FOF30\Form\Field\Model)
`apply_access` (same as FOF30\Form\Field\Model)

`leaf_only` (displayw but disable all parent nodes)
//$seperator 

Ends with JFormFieldList (https://api.joomla.org/cms-3/classes/JFormFieldList.html)
*/
		
class TreeSelect extends GenericList implements FieldInterface
{
	/**
	 * Options loaded from the model, cached for efficiency
	 *
	 * @var null|array
	 */
	protected static $loadedOptions = null;

	public function __set($name, $value)
	{
		// *NB this function sets properties of the field NOT Model data
		parent::__get($name, $value);
	}
	
	/**
     * Method to get the field options.
     *
     * @param   bool $forceReset
     *
     * @return  array The field option objects.
     */
	protected function getOptions($forceReset = false)
	{
		$myFormKey = $this->form->getName() . '#$#' . (string) $this->element['tree_select'];
		
		// Initialize some field attributes.
		$key             = $this->element['key_field'] ? (string) $this->element['key_field'] : 'value';
		$applyAccess     = StringHelper::toBool($this->element['apply_access']);
		$modelName       = (string) $this->element['model'];
		$leafOnly       = (string) $this->element['leaf_only'];
		$seperator       = ' - ';
		
		// Explode model name into component name and prefix
		$componentName = $this->form->getContainer()->componentName;
		$mName = $modelName;
		
		// Get the applicable container
		$container = $this->form->getContainer();

		if ($componentName != $container->componentName)
		{
			$container = Container::getInstance($componentName);
		}

		/** @var DataModel $model */
		$model = $container->factory->model($mName)->setIgnoreRequest(true)->savestate(false);

		// Get the model object
		if ($applyAccess)
		{
			$model->applyAccessFiltering();
		}
	
		
		$nestedListArray = $model->getRoot()->getNestedList($column = 'title', $key, $seperator);
		
		$options = array();
		
		// Loop through the nodes.
		if (!empty($nestedListArray))
		{
		
			//we could calc the depth by counting seperators... this is not ideal
			$lastDepth = 0;
			$disabled = false;
			
			foreach ($nestedListArray as $nodeId => $nodeTitle)
			{
				if($leafOnly)
				{
					//****This logic does not work****
					$depth = substr_count($optKey, $seperator);
					if($depth>$lastDepth)
					{
					
					}
					$lastDepth = $depth;
				}
				
				//we can rebuild the node's data and add in more options
				$tmp = array(
					'value'    => $nodeId,
					'text'     => $nodeTitle,
					'disable'  => $disabled
				);
				
				// Add the option object to the result set.
				$options[] = (object) $tmp;
			}
		}

		static::$loadedOptions[$myFormKey] = $options;

		return static::$loadedOptions[$myFormKey];
	}
	
}
