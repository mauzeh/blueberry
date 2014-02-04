<?php

/**
 * Allows user to dynamically construct a MySQL query in PHP.
 */
class Blueberry_Db_Query_Update extends Blueberry_Db_Query {

	protected $table = '';
	protected $data = array();
	protected $where = array();

	public function __construct($table = '', $data = array()){

		if($table != '') $this->setTable($table);
		if(count($data)) $this->setData($data);

	}

	public function setTable($table){

		$this->table = $table;

	}

	/**
	 *	Adds a WHERE clause to the query.
	 */
	public function where(){

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

		$this->where[] = call_user_func_array(
			'sprintf', array_merge($sql, $inserts)
		);

	}

	/**
	 * Adds a WHERE clause to the query, using an array as the condition.
	 */
	public function whereInSet($column, $set = array()){

		if( ! is_array($set)) return;
		$this->where[] = $column.' IN('.implode(',',$set).')';

	}

	public function setData($data){

		foreach($data as $key => $value){

			$key = '`'.$key.'`';

			if(is_object($value)){
				$this->data[$key] = $value->__toString();
			} else {
				$this->data[$key] = "'".mysql_real_escape_string(
					$value, Blueberry_Db_Adapter::getActiveConnection()
				)."'";
			}
		}
	}

	public function getQuery(){

		$this->query = "\n\nUPDATE ".$this->table." SET";

		foreach($this->data as $key => $value){

			$set[] = "\n\t".$key.' = '.$value;

		}

		$this->query .= implode(',', $set);

		// Add the where clauses
		if(is_array($this->where)){

			$i = 0;
			foreach($this->where as $where){

				if($i === 0){
					$this->query .= "\nWHERE";
				} else {
					$this->query .= "\nAND";
				}

				$this->query .= "\n\t".$where;
				$i++;

			}
		}

		return $this->query;

	}

	public function execute(){

		$this->query = $this->getQuery();
		parent::execute();

	}
}
