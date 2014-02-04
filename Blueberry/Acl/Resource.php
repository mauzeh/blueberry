<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents an Acl Resource in the database.
 *
 * This class is used in Blueberry_Acl that creates a
 * Zend_Acl implementation with a database back-end.
 */
class Blueberry_Acl_Resource extends Blueberry_Db_Object {

	protected $_name = 'acl_resource';
	
	public function __toString(){
	
		return $this->name;
	
	}
	
	public function delete(){
	
		$a = new Blueberry_Acl_Allowance_Collection(new Blueberry_Db_Query('SELECT * FROM acl_allowance WHERE resource_id = %d', $this->id));
		$a->delete();
		
		parent::delete();
	
	}
}

