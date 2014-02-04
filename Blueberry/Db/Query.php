<?php

/**
 * Wrapper for any MySQL query.
 */
class Blueberry_Db_Query {

	const FETCH_OBJECT = 'fetchObject';
	const FETCH_ARRAY = 'fetchArray';

    /**
     * @var int The amount of returned rows.
     */
	public $numrows;

    /**
     * @var string The actual MySQL query
     */
	protected $query;

    /**
     * @var resource The MySQL resource (for use with mysql_* functions)
     */
	protected $result;

    /**
     * @var bool False if the query is not yet executed, true otherwise.
     */
	protected $executed = false;

    /**
     * Creates a MySQL query by taking all arguments and sprintf()-ing them into
     * an escaped query.
     *
     * @param string $query The base query.
     * @param string $args Any arguments that will be escaped into the query.
     */
	public function __construct($query, $args = false){

		$args = func_get_args();
		$sql = array(array_shift($args));
		$inserts = array();

		foreach($args as $insert){

			if(is_object($insert)){
				$insert = $insert->__toString();
			} else {
				$insert = mysql_real_escape_string(
					$insert, Blueberry_Db_Adapter::getActiveConnection()
				);
			}

			$inserts[] = $insert;
		}

		$this->query = 
			call_user_func_array('sprintf', array_merge($sql, $inserts));

	}

    /**
     * Returns the number of rows that match the query's return result.
     *
     * @return int The number of rows.
     */
	public function numrows(){

		$this->execute();
		return $this->numrows;

	}

    /**
     * The id of the last inserted row by this query.
     *
     * @return int The id of the last inserted row.
     */
	public function getLastInsertId(){

		$this->execute();
		return $this->insert_id;

	}

    /**
     * Will run the query and potentially trigger errors by means of an
     * Exception.
     */
	public function execute(){

		// Only execute the query if it has not already been executed
		if($this->executed) return;

		$starttime = time() + microtime();

        $this->result = mysql_query(
	        $this->query, Blueberry_Db_Adapter::getActiveConnection()
        );
		$this->executed = true;
        $error = mysql_error();
        if($error){
            throw new Blueberry_Db_Exception(
	            $error.' (Query: '.$this->getReadableQuery().')'
            );
        }
        $this->numrows = @mysql_num_rows($this->result);
        $this->insert_id = @mysql_insert_id();

		if(defined('QUERY_PROFILER') && QUERY_PROFILER){

			$message = sprintf(
				't = %.4fs. Q: %s',
				time() + microtime() - $starttime,
				$this->query
			);

			Blueberry_Notice::raise('debug', $message);
		}
	}

    /**
     * Fetches an easy-to-read version of the query. Do not execute this because
     * it removes tabs and new lines, even in user entered content.
     */
    public function getReadableQuery(){

        return preg_replace('/(\n|\t)/', " ", $this->getQuery());

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

    /**
     * Fetches the value of the requested field. Only recommended when using
     * LIMIT 1.
     *
     * @param string $field The name of the column whose value we're fetching.
     * @return string The return value.
     */
	public function getvalue($field = 0)
	{
		$this->execute();
		return @mysql_result($this->result, 0, $field);
	}

    /**
     * Fetches the current row from the MySQL result.
     *
     * @return array The row from the MySQL result.
     */
	public function fetch($type = self::FETCH_ARRAY){

        // Improve speed by not trying to execute each time.
		if(!$this->executed) $this->execute();

		if($type == self::FETCH_ARRAY){
			return mysql_fetch_assoc($this->result);
		}
		if($type == self::FETCH_OBJECT){
			return mysql_fetch_object($this->result);
		}
	}

	/**
     * Fetches the current Blueberry_Db_Object instance from the MySQL result.
     *
     * @param string $className The name of the Blueberry_Db_Object subclass.
     * @param mixed $args Constructor arguments to the Blueberry_Db_Object subclass.
     * @return mixed The current Blueberry_Db_Object subclass.
     */
	public function fetchAs($className, $args = false){

		$args = func_get_args();
		$className = array_shift($args);

		if(!$this->executed){
            $this->execute();
        }

		$array = array();
		$row = $this->fetch();

		if($row == false) return false;

		// arguments you wish to pass to constructor of new object
		$constructorArgs = array_merge(array($row), $args);

		// make a reflection object
		$reflectionObj = new ReflectionClass($className);

        // use Reflection to create a new instance, using the $args
		$object = $reflectionObj->newInstanceArgs($constructorArgs);

		// this is the same as: new className('a', 'b');
		return $object;

	}

	/**
	 * Populates an array with all the objects in this result. It passed the
	 * array row to the constructor of each object and then adds the object
	 * to the array. Then it returns the array.
	 *
	 * If no objects were found it will return an empty array so as not to
	 * break up any foreach usage.
     *
     * @param string $className The name of the Blueberry_Db_Object subclass.
     * @param mixed $args Constructor arguments to the Blueberry_Db_Object subclass.
     * @return array An array filled with Blueberry_Db_Object subclasses.
     */
	public function fetchAll($className = '', $args = false){

        $args = func_get_args();
		$className = array_shift($args);

		if(!$this->executed) $this->execute();

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

    /**
     * Returns the underlying MySQL query.
     *
     * @return string The actual MySQL query.
     */
	public function getQuery(){
		return $this->query;
	}

	public function __toString(){
		return $this->getQuery();
	}

}
