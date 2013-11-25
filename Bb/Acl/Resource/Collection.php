<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents a collection of Acl Resources in the database.
 *
 * This class is used in Blueberry_Acl that creates a
 * Zend_Acl implementation with a database back-end.
 */
class Blueberry_Acl_Resource_Collection extends Blueberry_Db_Object_Collection {

	protected $_name = 'acl_resource';

}

