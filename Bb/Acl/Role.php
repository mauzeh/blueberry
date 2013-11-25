<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents an Acl Role in the database.
 *
 * This class is used in Blueberry_Acl that creates a
 * Zend_Acl implementation with a database back-end.
 */
class Blueberry_Acl_Role extends Blueberry_Db_Object {

	protected $_name = 'acl_role';
	
	public function init(){
		if(property_exists($this, 'parent_id')){
			$this->parent = new Blueberry_Acl_Role($this->parent_id);
		}
	}
	
	public function __toString(){
		return $this->name;
	}
}

