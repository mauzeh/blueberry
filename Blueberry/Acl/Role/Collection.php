<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents a collection of Acl Roles in the database.
 *
 * This class is used in Blueberry_Acl that creates a
 * Zend_Acl implementation with a database back-end.
 */
class Blueberry_Acl_Role_Collection extends Blueberry_Db_Object_Collection {

	protected $_name = 'acl_role';

	public function __construct($query = null){

		// order by parent_id to prevent problems with Zend_Acl (see constructor of Blueberry_Acl)
		if($query == null) $query = new Blueberry_Db_Query('SELECT * FROM %s ORDER BY parent_id', $this->_name);

		parent::__construct($query);

	}
}
