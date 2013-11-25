<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Db
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Runs a MySQL query
 *
 * @package Blueberry_Db
 */
class Blueberry_Db_Query {

    static $query_history = array();

	protected $query;
	protected $result;
	protected $executed = false;

	public $numrows;
	public $insertid;

	const FETCH_ASSOC = 'fetchAssoc';
	const FETCH_OBJECT = 'fetchObject';

	public function __construct($format)
	{
		$args = func_get_args();
		if(count($args) > 1){
			foreach($args as $key => $arg){
				// do not escape base query, and do not escape non-strings
                // (like Zend_Db_Expr)
				if($key > 0){
					if(is_string($arg))
                    {
                        $args[$key] = mysql_real_escape_string($arg);
                    }
					if(is_object($arg))
                    {
                        $args[$key] = $arg->__toString();
                    }
				}
			}
			// Then use sprintf (yes, always) to specify the rest
			$this->query = call_user_func_array('sprintf', $args);
		} else {
			// First argument is the base query
			$this->query = array_shift($args);
		}
	}

    /**
     * Returns true if the query has executed, false otherwise.
     */
    public function isExecuted(){
        return $this->executed;
    }

    /**
     * Undoes the execution of a query, enabling it to be reiterated.
     */
    public function reset(){
        $this->executed = false;
        $this->result = false;
    }

	public function execute()
	{
		if($this->isExecuted()){
			return;
		}
		$this->query = $this->getStatement();
		$this->result = mysql_query(
            $this->query, Blueberry_Db_Adapter::getActiveConnection()
        );
		$this->executed = true;

        self::$query_history[] = $this->query;

		$e = mysql_error();
		if($e){
			throw new Blueberry_Db_Exception(
                $e.'. Original query: ('.$this->query.')'
            );
		}
		if(is_resource($this->result)){
			$this->numrows = @mysql_num_rows($this->result);
		}
		$this->insertid = @mysql_insert_id();
	}

	public function fetch($type = self::FETCH_ASSOC)
    {
		$this->execute();
		switch($type){
			case self::FETCH_ASSOC : return mysql_fetch_assoc($this->result);
			case self::FETCH_OBJECT : return mysql_fetch_object($this->result);
		}
	}

    /**
     * Populates an array with all the objects in this result. It passed the
     * array row to the constructor of each object and then adds the object
     * to the array. Then it returns the array.
     *
     * If no objects were found it will return an empty array so as not to
     * break up any foreach usage.
     *
     * @param string $className The name of the Bb_Db_Object subclass.
     * @param mixed $args Any constructor arguments to the Bb_Db_Object
     *  subclass.
     * @return array An array filled with Bb_Db_Object subclasses.
     */
    public function fetchAll($className = '', $args = false){

        $args = func_get_args();
        $className = array_shift($args);

        $this->execute();

        $array = array();

        if($className == ''){
            while($row = $this->fetch()){
                $array[] = $row;
            }
            return $array;
        }

        while($row = $this->fetch()){

            // arguments you wish to pass to constructor of new object
            $constructorArgs = array_merge(array($row), $args);

            // make a reflection object
            $reflectionObj = new ReflectionClass($className);

            // use Reflection to create a new instance, using the $args
            $object = $reflectionObj->newInstanceArgs($constructorArgs);

            // this is the same as: new className('a', 'b');
            $array[] = $object;

        }

        return $array;

    }

	public function numrows()
    {
		$this->execute();
		return $this->numrows;
	}

	public function getValue()
    {
		$this->execute();
		return @mysql_result($this->result, 0);
	}

	public function getStatement()
    {
		return $this->query;
	}

	public function __toString()
    {
		return $this->getStatement();
	}
}
