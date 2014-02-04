<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents an Acl Allowance in the database.
 *
 * This class is used in Blueberry_Acl that creates a
 * Zend_Acl implementation with a database back-end.
 */
class Blueberry_Acl_Allowance extends Blueberry_Db_Object {

	protected $_name = 'acl_allowance';
	
	protected function init(){
	
		if($this->resource_id) $this->resource = new Blueberry_Acl_Resource($this->resource_id);
		$this->role = new Blueberry_Acl_Role($this->role_id);
	
	}
}

