<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Db
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Handles any insert/update actions for {@link Blueberry_Db_Object}s
 *
 * @tutorial Blueberry_Db_Object.pkg
 * @package Blueberry_Db
 */
abstract class Blueberry_Db_Object_Handler extends Zend_Db_Table_Abstract {

	protected function _setupTableName(){
		if(!$this->_name) $this->_name = strtolower(
			str_replace('_Handler', '', get_class($this))
		);
		parent::_setupTableName();
	}
	
	/**
	 * REPLACE is not supported by Zend because it is not database-agnostic.
	 * 
	 * Replace function to execute a MySQL REPLACE.
	 * @param array $data data array just as if it was for insert()
	 * @return Zend_Db_Statement_Mysqli
	 */
	public function replace($data) {
		// get the columns for the table
		$tableInfo = $this->info();
		$tableColumns = $tableInfo['cols'];
		
		// columns submitted for insert
		$dataColumns = array_keys($data);
		
		// intersection of table and insert cols
		$valueColumns = array_intersect($tableColumns, $dataColumns);
		sort($valueColumns);
		
		// generate SQL statement
		$cols = '';
		$vals = '';
		foreach($valueColumns as $col) {
			$cols .= $this->getAdapter()->quoteIdentifier($col) . ',';
			$vals .=	(get_class($data[$col]) == 'Zend_Db_Expr')
						? $data[$col]->__toString()
						: $this->getAdapter()->quoteInto('?', $data[$col]);
			$vals .= ',';
		}
		$cols = rtrim($cols, ',');
		$vals = rtrim($vals, ',');
		$sql = 'REPLACE INTO ' . $this->_name . ' (' . $cols . ') VALUES (' . $vals . ');';

		return $this->_db->query($sql);

	}
}