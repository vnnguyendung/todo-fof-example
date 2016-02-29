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
	
	/** @var  int|null  TreeModel the id of the parent node of ourselves */
	protected $treeParentId = null;
	
	/**
	 * Public constructor. Adds behaviours and sets up the behaviours and the relations
	 *
	 * @param   Container  $container
	 * @param   array      $config
	 */
	public function __construct(Container $container = null, array $config = array())
	{	
		parent::__construct($container, $config);
			
		$this->addKnownField('parent_id', 0, 'int');
		//$this->addSkipCheckField('parent_id');
	}
	
	// Model implementation goes here
	// i.e. getFieldnameAttribute,     
	protected function onAfterLoad($result, &$keys)
	{
		if($result){
			$pId = $this->getParentId();
			if($pId>0){
				$this->setFieldValue('parent_id', $pId);
			}
		}
    }
    
    // Add new node SPACE to TreeModel before the records are created
	protected function onBeforeCreate(&$data)
	{
		// TreeModel uses db queries so an item record must exist to manipulate the tree
		// A solution! Simply make space for the node ( see TreeModel->insertAsFirstChildOf() )

		//new Parent node id / default to root
		$newPId = $this->input->getInt('parent_id');//cannot access via $data (removed by recordDataToDatabaseData)
		$rootNode = $this->getRoot();
		$rootId = $rootNode->getId();
		
		if($newPId > $rootId)
		{
			$newPNode = $this->getNodeById($newPId);
		}
		else{
			$newPNode = $rootNode;
		}
		
		//Set `lft` & `rgt` to become first child of parent node
		$data->lft = $newPNode->lft+1;
		$data->rgt = $newPNode->lft+2;
		
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
    
    protected function onBeforeSave(&$data)
	{
		//updating a single node's position (with it's children) within the tree
		//probably best to call TreeModel->makeFirstChildOf() or TreeModel->makeLastChildOf()
		//allow selection of parent via a select list or js interactive tree (limit rearrange to current node)
		
		//REF: https://groups.google.com/forum/#!searchin/frameworkonframework/looping/frameworkonframework/tLdove-duWY/yA0dA6y4r5wJ
		static $allowRecursion = true;

		if($allowRecursion){
			
			//new Parent node id
			$newPId = $data['parent_id'];
			
			$pId = $this->getParentId();

			if($newPId >0 && $pId >0)
			{
				//check the node needs moving
				if($pId != $newPId && $allowRecursion)
				{
					$allowRecursion = false;
			
					$newPNode = $this->getNodeById($newPId);
				
					$this->makeFirstChildOf($newPNode);
					
					//Set `lft` & `rgt` so save doesn't overwrite them					
					$this->recordData['lft'] = $data['lft'] = $this->lft;
					$this->recordData['rgt'] = $data['rgt'] = $this->rgt;
										
					$updates = array(
						'lft'		=> $this->lft,
						'rgt' 		=> $this->rgt
					);
					$this->bind($updates);
				}
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
	
	public function getParentId()
	{
		if(empty($this->treeParentId))
		{	
			if($this->getId())
			{
				$this->treeParentId = $this->getClone()->getParent()->getId();
			}
		}
		
		return $this->treeParentId;
	}
    
    //https://groups.google.com/forum/#!searchin/frameworkonframework/setfieldvalue/frameworkonframework/hwr0V1DLdsQ/RAo0etvoBgAJ
    public function recordDataToDatabaseData()
	{
	   $copy = parent::recordDataToDatabaseData();
   
	   if (isset($copy['parent_id']))
	   {
		  unset ($copy['parent_id']);//remove `parent_id` as column doesn't actually exist
	   }
   
	   return $copy;
	}	
}