<?php

class Blueberry_Db_Query_Select extends Blueberry_Db_Query {

    protected $columns = array();
    protected $tables = array();
    protected $conditions = array();
    protected $orderByClauses = array();
    protected $groupByClauses = array();
    protected $limitClause = '';
    protected $fetchObjectName = '';

    public function __construct(){
    }

    /**
     * Checks whether the query can still be manipulated and throws a
     * Blueberry_Db_Exception if it can't.
     */
    protected function ensureMutability()
    {
		if($this->executed){
			throw new Blueberry_Db_Exception('Query is already executed, its
											 data cannot be modified anymore.');
		}
    }

    public function setFetchObjectName($name)
    {
		$this->ensureMutability();
        $this->fetchObjectName = $name;
    }

	public function fetch($type = self::FETCH_ASSOC)
    {
        $class = $this->fetchObjectName;

		if(!$this->executed) $this->execute();
        if(!class_exists($class)) return parent::fetch($type);

        $data = mysql_fetch_assoc($this->result);

        if($data != false) return new $class($data);
        else return false;

	}

    public function addColumn($column){
		$this->ensureMutability();
        $this->columns[] = $column;
    }

    public function addTables(){
		$this->ensureMutability();
        foreach(func_get_args() as $table){
            $this->addTable($table);
        }
    }

    public function addTable($table){
		//p($table);
		$this->ensureMutability();
        $this->tables[] = $table;
    }

    public function addWhere($condition, $args = ''){

		$this->ensureMutability();

		$args = func_get_args();

		if(count($args) > 1){

			foreach($args as $key => $arg){

				// Do not escape base query and do not escape non-strings
                // (like Zend_Db_Expr)
				if($key > 0){

					if(is_string($arg)) $args[$key] = mysql_real_escape_string($arg);
					if(is_object($arg)) $args[$key] = $arg->__toString();

				}

			}

			// Then use sprintf (yes, always) to specify the rest
			$condition = call_user_func_array('sprintf', $args);

		} else {

			// First argument is the base query
			$condition = array_shift($args);

		}

        $this->conditions[] = $condition;

    }

    public function addOrderByClause($clause){
		$this->ensureMutability();
        $this->orderByClauses[] = $clause;
    }

    public function addGroupByClause($clause){
		$this->ensureMutability();
        $this->groupByClauses[] = $clause;
    }

    public function limit($start, $amount = ''){
		$this->ensureMutability();
        $this->limitClause = $start;
        $this->limitClause .= $amount ? ','.$amount : '';
    }

    public function clearOrderByClauses(){
		$this->ensureMutability();
        $this->orderByClauses = array();
    }

    public function getOrderByClauses(){
		$this->ensureMutability();
        return $this->orderByClauses;
    }

    public function hasLimit(){
        return strlen($this->limit) > 0;
    }

	public function getStatement(){

		$string = 'SELECT ';
        $string .= count($this->columns) ? implode(',', $this->columns) : '*';
        $string .= ' FROM ';
        $string .= implode(',', $this->tables);

        if(count($this->conditions)){
            $string .= ' WHERE '.implode(' AND ', $this->conditions);
        }

        if(count($this->groupByClauses)){
            $string .= ' GROUP BY '.implode(',', $this->orderByClauses);
        }

        if(count($this->orderByClauses)){
            $string .= ' ORDER BY '.implode(',', $this->orderByClauses);
        }

        if($this->limitClause){
            $string .= ' LIMIT '.$this->limitClause;
        }

        return $string;

	}
}
