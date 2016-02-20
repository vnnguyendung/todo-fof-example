<?php
/**
 * @package   To-do
 * @copyright Copyright (c)2013-2015 Nicholas K. Dionysopoulos / AkeebaBackup.com (addition from FOF3 community)
 * @license   GNU General Public License version 2 or later
 */
 
 /**
 * ===DOCUMENTAION=== 
 * https://github.com/akeeba/fof/wiki/The-Model
 * https://github.com/akeeba/fof/wiki/The-TreeModel
 * 
 * https://github.com/akeeba/fof/wiki/Using-Joomla%21-plugins-to-handle-FOF-events
 * 
 * Tip: You can see all the events called by a FOF class, e.g. FOF30\Model\DataModel,
 * by opening its code and searching for triggerEvent.
 * Remember to also check its parent class, e.g. FOF30\Model\Model,
 * in case some of the events are defined there.
 */
namespace Akeeba\Todo\Admin\Model;

use FOF30\Container\Container;

class Categories extends \FOF30\Model\TreeModel
{

	/** @var  int|null  This node's parent's id */
	protected $pNodeId = null;
	
	/**
	 * Public constructor. Adds behaviours and sets up the behaviours and the relations
	 *
	 * @param   Container  $container
	 * @param   array      $config
	 */
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		//add new knownField
		$this->addKnownField('parent_id');
	}
	
	// Model implementation goes here
	// i.e. getFieldnameAttribute, 
    	
    // Add new node SPACE to TreeModel before the records are created
	protected function onBeforeCreate(&$data)
	{
		// TreeModel uses db queries so an item record must exist to manipulate the tree
		// A solution! Simply make space for the node ( see TreeModel->insertAsFirstChildOf() )
		
		$newPId = $this->input->getInt('parent_id');//not available via $data
		$rootId = $this->getRoot()->getId();
		if($newPId > $rootId)
		{
			$newPNode = $this->getNodeById($newPId);
			
			//Set `lft` & `rgt` to become first child of $newPNode
			$data->lft = $newPNode->lft+1;
			$data->rgt = $newPNode->lft+2;
		}
		
		// lft insertion point
		$insertLeft = $data->lft-1;
		
		// Get a reference to the database
		$db = $this->getDbo();
			
		// Get the field names
		$fldRgt = $db->qn($this->getFieldAlias('rgt'));
		$fldLft = $db->qn($this->getFieldAlias('lft'));
		
		// Wrap everything in a transaction
		$db->transactionStart();

		try
		{
			// Make a hole (2 queries)
			$query = $db->getQuery(true)
				->update($db->qn($this->tableName))
				->set($fldLft . ' = ' . $fldLft . '+2')
				->where($fldLft . ' > ' . $db->q($insertLeft));
			$db->setQuery($query)->execute();
			
			$query = $db->getQuery(true)
				->update($db->qn($this->tableName))
				->set($fldRgt . ' = ' . $fldRgt . '+ 2')
				->where($fldRgt . '>' . $db->q($insertLeft));
			$db->setQuery($query)->execute();

			// Commit the transaction
			$db->transactionCommit();
		}
		catch (\Exception $e)
		{
			// Roll back the transaction on error
			$db->transactionRollback();

			throw $e;
		}
	}
	
	// Move node when TreeModel is updated
	protected function onBeforeUpdate(&$data)
	{
		//updating a single node's position (with it's children) within the tree
		//probably best to call TreeModel->makeFirstChildOf() or TreeModel->makeLastChildOf()
		//allow selection of parent via a select list or js interactive (limited to current node) tree
		
		$newPId = $this->input->getInt('parent_id');//not available via $data
		//$newPId = $data->parent_id; //$data does not hold the addKnownField with this databaseDataToRecordData & recordDataToDatabaseData usage
		
		if($newPId)
		{
			//check the node needs moving
			if($this->getPId() != $newPId)
			{
				$newPNode = $this->getNodeById($newPId);
				$this->makeFirstChildOf($newPNode);
			}
		}
		
		//a view would be possible for totally rearranging the TreeModel with js
		//**However this must be limited to superadmin or always show ALL nodes to avoid errors
		//this would simply rewrite all lft & rgt columns based on js tree (via json)
	}
	
	protected function getNodeById($id)
	{
		return $this->getClone()->reset()->find($id);
	}
	
	protected function getPId()
	{
		if($this->getId())
		{	
			$this->pNodeId = $this->getParent()->getId();
		}
		return $this->pNodeId;
	}
	
	public function databaseDataToRecordData()
	{
		foreach ($this->recordData as $name => $value)
		{
			$method = $this->container->inflector->camelize('get_' . $name . '_attribute');
			if (method_exists($this, $method))
			{
				$this->recordData[$name] = $this->{$method}($value);
			}
		}
		
		$this->recordData['parent_id'] = $this->getPId();	
	}
	
	public function recordDataToDatabaseData()
	{
		$copy = array_merge($this->recordData);
		unset($copy['parent_id']);//remove `parent_id` as column doesn't exist
		
		foreach ($copy as $name => $value)
		{
			$method = $this->container->inflector->camelize('set_' . $name . '_attribute');
			if (method_exists($this, $method))
			{
				$copy[$name] = $this->{$method}($value);
			}
		}
		return $copy;
	}
}