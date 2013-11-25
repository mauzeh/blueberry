<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Db
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Fetches one row (object) in a MySQL database table.
 *
 * <b>For detailed information about using this class, check the tutorial 
 * "{@tutorial Blueberry_Db_Object.pkg}".</b>
 *
 * This class is not meant to be instantiated directly. To use
 * it, you'll need to extend it. If you leave the declaration of 
 * the extended class empty (see example), then Blueberry
 * automatically finds the object by its primary key and extracts
 * the data from the corresponding MySQL table.
 *
 * By default, Blueberry derives the name of the MySQL table
 * {@link tableName automatically} based on the name of your
 * extended class.
 *
 * @tutorial Blueberry_Db_Object.pkg
 * @package Blueberry_Db
 */
abstract class Blueberry_Db_Object {

	/**
	 * The MySQL table from which to fetch the data.
	 *
	 * Defaults to <samp>strtolower(</samp><i>classname</i><samp>)</samp>.
	 *
	 * @var string
	 */
	protected $_name = null;
	
	/**
	 * The primary column of the corresponding table.
	 *
	 * Defaults to 'id'.
	 *
	 * @var string
	 */
	protected $_primary = null;
	protected $_criterion = null;
	protected $_query = null;
	protected $_data = array();

	/**
	 * Automatically fills the object with data.
	 * 
	 */
	public function __construct($criterion){
		
		$this->_criterion = $criterion;

		if($this->_name == null) $this->_name = strtolower(get_class($this));
		if($this->_primary == null) $this->_primary = 'id';
		
		$this->load();
		
	}
	
	protected function _initQuery()
	{
		if($this->_query == null){
			if($this->_criterion instanceof Blueberry_Db_Query){
				$this->_query = $this->_criterion;
			} else {
				$this->_query = new Blueberry_Db_Query(
					"SELECT * FROM `%s` WHERE `%s` = '%s'",
					$this->_name, $this->_primary, $this->_criterion
				);
			}
		}
		
		$this->_query->reset();
		$this->_query->execute();

		if($this->_query->numrows() < 1){
			throw new Blueberry_Db_Exception(sprintf(
				'Cannot find object of type %s, the database does not '.
				'contain any row where %s = %s. Original query: %s',
				get_class($this),
				$this->_primary,
				$this->_criterion,
				$this->_query->getStatement()
			));
		}

		if($this->_query->numrows() > 1){
			throw new Blueberry_Db_Exception(sprintf(
				'Cannot find object of type %s, the database returned '.
				'more than one row where %s = %s. Original query: %s',
				get_class($this),
				$this->_primary,
				$this->_criterion,
				$this->_query->getStatement()
			));
		}
	}
	
	public function reloadData(){
		$this->load();
	}
	
	protected function load(){

		if(is_array($this->_criterion)){
			$this->_data = $this->_criterion;
		} else {
			$this->_initQuery();
			$this->_data = $this->_query->fetch();
		}
		
		$this->createFromData();
		$this->init();
	}
	
	/**
	 * Fills this object with values
	 */ 
	protected function createFromData(){
		if(empty($this->_data) || !count($this->_data)){
			throw new Blueberry_Db_Exception(sprintf(
				'Cannot use empty data array to create object of type %s',
				get_class($this)
			));
		}

		foreach($this->_data as $key => $value){
			$this->$key = stripslashes($value);
		}
	}
	
	/**
	 * Removes the object from the database
	 */
	public function delete(){
	
		$primary = $this->_primary;
		$q = new Blueberry_Db_Query(
			"DELETE FROM `%s` WHERE `%s` = '%s' LIMIT 1",
			$this->_name, $this->_primary, $this->$primary
		);
		$q->execute();
	
	}
	
	/**
	 * This method is called at the end of the hydration process.
	 * 
	 * When inheriting the user can use this method to make alterations to the
	 * data from the database.
	 */
	protected function init(){
	
	
	}
	
	public function __toString(){return serialize($this);}
	
}