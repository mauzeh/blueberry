<?php

/**
 * Allows user to dynamically construct a MySQL query in PHP.
 */
class Blueberry_Db_Query_Select extends Blueberry_Db_Query {

	protected $orderBy	= array();
	protected $groupBy	= array();
	protected $columns	= array();
	protected $where	  = array();
	protected $tables	 = array();
	protected $limit	  = array();
	protected $distinct   = false;

	public function __construct($table = '')
	{
		if(strlen($table)){
			$this->addTable($table);
		}
	}

	public function addColumn($column)
	{
		$this->columns[] = $column;
	}

	public function addTable($table)
	{
		$this->tables[] = $table;
	}

	public function hasLimit()
	{
		return ! empty($this->limit);
	}

	public function addTables($args)
	{
		foreach(func_get_args() as $table){
			if(is_array($table)){
				$this->addTables($table);
				continue;
			}
			$this->addTable($table);
		}
	}

	public function clearColumns()
	{
		$this->columns = array();
	}

	/**
	 *	Adds a WHERE clause to the query.
	 */
	public function where()
	{
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
	public function whereInSet($column, $set = array())
	{
		if( ! is_array($set)){
			return;
		}
		$this->where[] = $column.' IN('.implode(',',$set).')';
	}

	/**
	 * Adds a WHERE clause to the query, using an array as the condition.
	 */
	public function whereNotInSet($column, $set = array())
	{
		if(!is_array($set)) return;

		$this->where[] = $column.' NOT IN('.implode(',',$set).')';
	}

	public function groupBy($column)
	{
		$args = func_get_args();
		$column = array(array_shift($args));

		$this->groupBy[] = call_user_func_array(
			'sprintf', array_merge($column, $args)
		);
	}

	public function orderBy($column = false, $direction = 'asc')
	{
		// Reset order by
		if($column == false){
			$this->orderBy = array();
			return;
		}

		// Escape column name
		$newColumn = array();
		foreach(explode('.', $column) as $part){
			$newColumn[] .= '`'.$part.'`';
		}

		if($column){
			$this->orderBy[] = array(
				'column' => implode('.', $newColumn),
				'direction' => strtoupper($direction)
			);
		}
	}

	public function getOrderBy()
	{
		return $this->orderBy;
	}

	public function limit($min = false, $max = false)
	{
		// If no params, then remove all limits
		if($min == false && $max == false){
			$this->limit = '';
		}

		if($min == 0 && $max > 0 || $min > 0){

			$this->limit = ' LIMIT '.(int)$min;
			if($max) $this->limit .= ','.$max;

		}
	}

	public function makeDistinct()
	{
		$this->distinct = true;
	}

	public function getQuery(){

		$this->query = "SELECT";

		if(count($this->columns) && $this->distinct){
			$this->query .= ' DISTINCT ';
		}

		if(!count($this->columns)) $this->query .= ' * ';

		$i = 0;
		foreach($this->columns as $column){
			if($i > 0) $this->query .= ",";
			$this->query .= "\n\t".$column;
			$i++;
		}

		$this->query .= "\nFROM";

		$i = 0;
		foreach($this->tables as $table){
			if($i > 0) $this->query .= ',';
			$this->query .= "\n\t".$table;
			$i++;
		}
		
		// Add the where clauses
		if(is_array($this->where)){

			$i = 0;
			foreach($this->where as $where){

				if($i === 0){
					$this->query .= "\nWHERE";
				} else {
					$this->query .= "\nAND";
				}
				$this->query .= "\n\t(".$where.")";
				$i++;

			}
		}

		// Add the groupBy clauses
		if(is_array($this->groupBy)){

			$i = 0;
			foreach($this->groupBy as $groupBy){

				if($i > 0){
					$this->query .= ' , ';
				} else {
					$this->query .= "\nGROUP BY";
				}
				$this->query .= " ".$groupBy;
				$i++;

			}
		}

		// Add the orderBy clauses
		if(!empty($this->orderBy)){

			$this->query .= "\nORDER BY";

			foreach($this->orderBy as $i => $orderBy){

				if($i > 0){
					$this->query .= ',';
				}

				$this->query .= sprintf(
					" %s %s", $orderBy['column'], $orderBy['direction']
				);
			}
		}

		if(!empty($this->limit)) $this->query .= $this->limit;

		return $this->query;

	}

	public function execute(){

		// Only execute the query if it has not already been executed
		if($this->executed) return;

		$this->getQuery();
		parent::execute();

	}
}
