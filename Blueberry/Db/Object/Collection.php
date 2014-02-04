<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Db
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Loads a {@link Blueberry_Collection} containing objects from a MySQL database.
 *
 * <b>For detailed information about using this class, check the tutorial
 * "{@tutorial Blueberry_Db_Object_Collection.pkg}".</b>
 *
 * This class is not meant to be instantiated directly. To use
 * it, you'll need to extend it. If you leave the declaration of
 * the extended class empty (see example), then Blueberry
 * automatically populates a list with all objects in the
 * corresponding MySQL table.
 *
 * By default, Blueberry derives the name of the MySQL table
 * {@link tableName automatically} based on the name of your
 * extended class.
 *
 * @tutorial Blueberry_Db_Object_Collection.pkg
 * @package Blueberry_Db
 * @abstract
 */
abstract class Blueberry_Db_Object_Collection extends Blueberry_Collection {

	/**
	 * The MySQL table from which to fetch the data.
	 *
	 * Defaults to <samp>strtolower(</samp><i>classname</i><samp>)</samp>
	 *
	 * Examples:
	 * <code>
	 * // will automatically fetch data from table "customer"
	 * class Customer_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will automatically fetch data from table "dog_tail"
	 * class Dog_Tail_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will automatically fetch data from table "airlinepilot"
	 * class AirlinePilot_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will automatically fetch data from table "pilot"
	 * class AirlinePilot_Collection extends Blueberry_Db_Object_Collection {
	 *
	 *	protected $_name = 'pilot';
	 *
	 * }
	 * </code>
	 *
	 * @var string
	 */
	protected $_name = null;

	/**
	 * The {@link Blueberry_Db_Query} populates the collection with objects.
	 *
	 * Defaults to a query that will fetch all items from the table
	 * with no ordering.
	 *
	 * @var Blueberry_Db_Query
	 */
	protected $_query = null;

	/**
	 * The class name of the linked element class, if it exists.
	 *
	 * Defaults to the name of this class, minus the '_Collection' part. So
	 * in the case of 'Customer_Collection', this variable would automatically
	 * become 'Customer'.
	 *
	 * Examples:
	 * <code>
	 * // will search for linked element class "Customer"
	 * class Customer_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will search for linked element class "Dog_Tail"
	 * class Dog_Tail_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will search for linked element class "AirlinePilot"
	 * class AirlinePilot_Collection extends Blueberry_Db_Object_Collection {}
	 *
	 * // will search for linked element class "Pilot"
	 * class AirlinePilot_Collection extends Blueberry_Db_Object_Collection {
	 *
	 *	protected $_elementClassName = 'Pilot';
	 *
	 * }
	 * </code>
	 *
	 * @see _hasLinkedObject
	 * @var string
	 */
	protected $_elementClassName = null;

	/**
	 * Automatically fills the Collection with objects.
	 *
	 * If you pass the constructor a {@link Blueberry_Db_Query}
	 * object then Blueberry will use your query instead of the
	 * default query (which selects all rows in the table) to
	 * fill the collection with elements.
	 *
	 * @tutorial Blueberry_Db_Object_Collection.pkg
	 * @param Blueberry_Db_Query $query the query that overrides {@link _query the default query}
	 */
	public function __construct(Blueberry_Db_Query $query = null)
    {
		$this->_setupElementClassName();
		if($query == null){
			$this->_setupName();
			$this->_setupQuery();
		} else {
			$this->_query = $query;
		}
		$this->_populate();
	}

	/**
	 * Populates the collection.
	 */
	protected function _populate()
    {
		if($this->_hasLinkedObject()){
			if(!class_exists($this->_elementClassName)){
				throw new Blueberry_Db_Object_Collection_Exception(
					'('.get_class($this).') Undefined element class '.
					$this->_linkedElementClass
				);
			}
			while($row = $this->_query->fetch()){
				// to be inserted soon
				// if(count($row) < 2) throw new Blueberry_Db_Exception('Cannot create '.get_class($this).' because query does not fetch enough columns. Original query: "'.$this->_query->getStatement().'"');
				// backward compat fix for queries that return just the id
				if(count($row) < 2) $row = array_pop($row);
				$this->add(new $this->_elementClassName($row));
			}
		} else {
			while($element = $this->_query->fetch(
				Blueberry_Db_Query::FETCH_OBJECT
			)){
				$this->add($element);
			}
		}
	}

	/**
	 * Returns true if there is a suitable linked element class.
	 *
	 * A linked element class is a class that defines the elements of this collection.
	 * Its name defaults to the name of this class, minus the '_Collection' part. So
	 * in the case of 'Customer_Collection', this variable would automatically
	 * become 'Customer'. You may override this default behavior by overriding the
	 * {@link _elementClassName} property in your subclassed collection.
	 *
	 * Blueberry will only acknowledge a linked element class if it is a subclass of
	 * {@link Blueberry_Db_Object}.
	 */
	protected function _hasLinkedObject()
    {
		return @is_subclass_of(
			$this->_elementClassName, 'Blueberry_Db_Object'
		);
	}

	/**
	 * Generates the default MySQL table name.
	 * @see _name
	 */
	protected function _setupName()
    {
		if(!$this->_name){
			$this->_name = strtolower(preg_replace(
				'/_Collection/i', '', get_class($this)
			));
		}
	}

	/**
	 * Generates the default linked element class name.
	 * @see _elementClassName
	 */
	protected function _setupElementClassName()
    {
    	if(!$this->_elementClassName){
			$this->_elementClassName = preg_replace(
				'/_Collection/i', '', get_class($this)
			);
		}
	}

	/**
	 * Generates the default query to fetch the elements.
	 * @see _query
	 */
	protected function _setupQuery(Blueberry_Db_Query $query = null)
    {
		if($query){
			$this->_query = $query;
		} else {
			$this->_query = new Blueberry_Db_Query_Select($this->_name);
		}
	}

	/**
	 * Deletes all elements from the database.
	 *
	 * This method can only be used if the collection is made up of
	 * {@link Blueberry_Db_Object}s.
	 */
	public function delete()
    {
		foreach($this as $e){

			if(method_exists($e, 'delete')){

				$e->delete();

			} else {
				throw new Blueberry_Db_Object_Collection_Exception(
					'Cannot delete '.get_class($e).' as element of '.
					get_class($this).' because '.get_class($e).'::delete() is '.
					'not defined.'
				);
			}
		}
	}
}