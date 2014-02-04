<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Acl
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Zend_Acl implementation with database back-end.
 *
 * A Zend_Acl implementation using a database as
 * source for the definition of roles, resources
 * and allowances.
 *
 * To use this class, create the following MySQL
 * tables:
 *
 * <code>
 * CREATE TABLE IF NOT EXISTS `acl_allowance` (
 * `id` int(11) NOT NULL auto_increment,
 * `role_id` int(11) NOT NULL,
 * `resource_id` int(11) default NULL,
 * `editable` tinyint(1) NOT NULL default '0',
 * `action` varchar(256) collate latin1_general_ci default NULL,
 * `allow` tinyint(1) NOT NULL,
 * PRIMARY KEY  (`id`)
 * ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=23 ;
 * 
 * CREATE TABLE IF NOT EXISTS `acl_resource` (
 * `id` int(11) NOT NULL auto_increment,
 * `editable` tinyint(1) NOT NULL default '0',
 * `name` varchar(256) collate latin1_general_ci NOT NULL,
 * PRIMARY KEY  (`id`)
 * ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;
 * 
 * CREATE TABLE IF NOT EXISTS `acl_role` (
 * `id` int(11) NOT NULL auto_increment,
 * `editable` tinyint(1) NOT NULL default '0',
 * `name` varchar(256) collate latin1_general_ci NOT NULL,
 * `parent_id` int(11) default NULL,
 * `is_default` tinyint(1) NOT NULL default '0',
 * PRIMARY KEY  (`id`)
 * ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=7 ;
 * </code>
 *
 * @see Blueberry_Acl_Role
 * @see Blueberry_Acl_Resource
 * @see Blueberry_Acl_Allowance
 */
class Blueberry_Acl extends Zend_Acl {

	const WILDCARD_CHARACTER = '*';

	private $acl;

	/**
	 * Initializes the object.
	 *
	 * Does not require input arguments.
	 */
	public function __construct(){
	
		$roles = new Blueberry_Acl_Role_Collection();
		$resources = new Blueberry_Acl_Resource_Collection();
		$allowances = new Blueberry_Acl_Allowance_Collection();

		foreach($roles as $r){
		
			if($r->parent_id) $this->addRole(new Zend_Acl_Role($r->id), $r->parent_id);
			else $this->addRole(new Zend_Acl_Role($r->id));
			
		}
		
		foreach($resources as $r) $this->add(new Zend_Acl_Resource($r->name));

		foreach($allowances as $a){
		
			if($a->resource->name) $resource = $a->resource->name;
			else $resource = null;
			
			if($a->action != self::WILDCARD_CHARACTER) $action = $a->action; else $action = null;
		
			if($a->allow) $this->allow($a->role_id, $resource, $action);
			else $this->deny($a->role_id, $resource, $action);
			
		}
	}
}

