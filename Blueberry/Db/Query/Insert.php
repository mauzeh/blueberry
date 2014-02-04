<?php

/**
 * Allows user to dynamically construct a MySQL query in PHP.
 */
class Blueberry_Db_Query_Insert extends Blueberry_Db_Query {

	protected $table = '';
	protected $data = array();
	protected $action = 'INSERT';

	public function __construct($table = '', $data = array()){

		if($table != '') $this->setTable($table);
		if(count($data)) $this->setData($data);

	}

	public function setTable($table){

		$this->table = $table;

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

		$this->query = $this->action." INTO ".$this->table;
		$this->query .= "\n\t(\n\t";
		$this->query .= implode(",\n\t", array_keys($this->data))."\n\t)";
		$this->query .= "\nVALUES";
		$this->query .= "\n\t(\n\t";
		$this->query .= implode(",", array_values($this->data))."\n\t)";

		return $this->query;

	}

	public function execute(){

		$this->getQuery();

		parent::execute();

	}
}
