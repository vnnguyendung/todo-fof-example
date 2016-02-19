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

class Categories extends \FOF30\Model\TreeModel
{
	
	// Model implementation goes here
	// i.e. getFieldnameAttribute, 
    protected function onAfterLoad(&$data)
	{
		//$this->addKnownField('position');
		
		//set the default to by the parent node?
		//$this->getParent();
		//$data->tree;
	}
	
    // Add new node to TreeModel when created
	protected function onBeforeCreate(&$data)
	{
		
		//onBefore seemed sensible but the TreeModel uses db queries so an item record must exist to manipulate the tree
		parent::onBeforeCreate($data);
		
		// Item creation submission fails if no lft & rgt form values so cannot set defaults here
		//$data->lft=2;
		//$data->rgt=3;
		
		
		// A solution! Simply make space for the node ( see TreeModel->insertAsFirstChildOf() )

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
	
	//
	protected function onAfterCreate(&$data)
	{
		//** onAfter an item record exists but TreeModel hasn't "made space" for it...
	}
	
	// Move node when TreeModel is updated
	protected function onBeforeUpdate(&$data)
	{
		//updating a single node's position (with children) within the tree
		//probably best to call TreeModel->makeFirstChildOf() or TreeModel->makeLastChildOf()
		//allow selection of parent via a select list or js interactive (limited to current node) tree
		
		$this->addKnownField('position');
		dump($data->position, 'position');
		$newPId = $data->position;
		
		if($newPId)
		{
			$pNode = $this->getParent();
			//check the node needs moving
			if($pNode->getId() != $newPId)
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
}