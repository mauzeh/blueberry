<?php

/**
 * Allows user to dynamically construct a MySQL query in PHP.
 */
class Blueberry_Db_Query_Delete extends Blueberry_Db_Query_Select {

	public function getQuery(){

		$this->query = "DELETE FROM";

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
				$this->query .= "\n\t".$where;
				$i++;

			}
		}
		return $this->query;
	}

	public function execute(){

		$this->getQuery();
		parent::execute();

	}
}
