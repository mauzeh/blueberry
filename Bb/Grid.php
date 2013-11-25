<?php

class Blueberry_Grid {

	protected $query = null;
	protected $collection = null;
	protected $columns = null;
	protected $footer = null;
	protected $pager = null;

    protected $identifier = '';

    protected $string = '';

	/**
	 * Grid instatiation.
	 *
	 * Configuration:
	 * $config['columns'] : A Blueberry_Collection or array filled with Blueberry_Grid_Column objects.
	 *
	 *
	 * @param array $config The configuration of this Grid.
	 */
	public function __construct($config = array()){

		$this->setColumns($config['columns']);

		if($config['identifier']) $this->identifier = $config['identifier'];
        if($config['footer']) $this->footer = $config['footer'];
		if($config['query']) $this->setQuery($config['query']);
		if($config['collection']) $this->setCollection($config['collection']);
		if($config['pager']) $this->setPager($config['pager']);

        $this->setString();

	}

    protected function setPager(Blueberry_Grid_Pager $pager){

		if(!($this->query instanceof Blueberry_Db_Query_Select)){

			throw new Blueberry_Grid_Exception('Can use pager only in combination
											    with a Blueberry_Db_Query_Select
												query');

		}

		if($this->query->isExecuted()){

			throw new Blueberry_Grid_Exception('Cannot use pager in combination
											    with a query that is already executed.');

		}

		if($this->query->hasLimit()){

			throw new Blueberry_Grid_Exception('Cannot use pager in combination
											    with a query that has a LIMIT clause.');

		}

		$cloneQuery = clone $this->query;
		$cloneQuery->execute();

        $this->pager = $pager;
		$this->pager->setTotalRows($cloneQuery->numrows);

		$this->query->limit($this->pager->getCurrentRow(),
							$this->pager->getRowsPerPage());

		// Apply ordering (BEFORE query execution)
		$this->order();

		// Execute query to throw Exceptions BEFORE __toString() is called to
		// avoid PHP Fatal Error "__toString() must not throw an exception"
		$this->query->execute();

		$this->footer->prepend(new Blueberry_Grid_Footer_Element($pager,
											  Blueberry_Grid_Footer_Element::
											  ALIGN_RIGHT));

    }

	public function setQuery(Blueberry_Db_Query $query){

		if($this->collection != null) throw new Blueberry_Grid_Exception('Cannot set query, collection was already set');
		if($this->query      != null) throw new Blueberry_Grid_Exception('Cannot set query, query was already set');

		$this->query = $query;

	}

	public function setCollection(Blueberry_Collection $collection){

		if($this->collection != null) throw new Blueberry_Grid_Exception('Cannot set collection, collection was already set');
		if($this->query      != null) throw new Blueberry_Grid_Exception('Cannot set collection, query was already set');

		$this->collection = $collection;

	}

	protected function getNextObject(){

		static $i = 0;

		if($this->collection != null){

			return $this->collection->get($i++);

		} elseif($this->query != null){

			return $this->query->fetch(Blueberry_Db_Query::FETCH_OBJECT);

		} else {

			throw new Blueberry_Grid_Exception('No data defined');

		}
	}

	public function setColumns($columns){

		if(!is_array($columns) && !($columns instanceof Blueberry_Collection)) throw new Blueberry_Grid_Exception('Columns must be array or Blueberry_Collection');

		foreach($columns as $column){

			if(!($column instanceof Blueberry_Grid_Column)){

				throw new Blueberry_Grid_Exception('Column must inherit from
												   Blueberry_Grid_Column');

			}

		}

		$this->columns = $columns;

	}

    /**
     * Fetches the column by name.
     *
     * @param string $name The column name.
     */
    public function getColumnByName($name){

        foreach($this->columns as $column){

			if($column->getName() == $name) return $column;

		}

		throw new Blueberry_Grid_Exception("Unable to fetch column by name '".$name."'.");

    }

    /**
     * Injects ORDER BY clause in query and flags the order column.
     *
     * Assumes that all sorting is done on ONE column.
     */
    public function order(){

		// If order column has been set in url
		if($_GET['__blueberry_grid_'.$this->identifier.'_orderby']){

			$name = $_GET['__blueberry_grid_'.$this->identifier.'_orderby'];
			$direction = $_GET['__blueberry_grid_'.$this->identifier.'_orderdirection'] ==
				Blueberry_Grid_Column::ORDER_ASC ?
				Blueberry_Grid_Column::ORDER_ASC :
				Blueberry_Grid_Column::ORDER_DESC;

            // If someone injected a strange name
            try {

                $this->getColumnByName($name);

                // Do not allow multiple-column order
                $this->query->clearOrderByClauses();
                $this->query->addOrderByClause($name.' '.$direction);

            } catch(Exception $e){

                $name = '';

            }
        }

		// If not successful, try to fetch it from the original query
		if(strlen($name) == 0){

			$orderByClauses = $this->query->getOrderByClauses();

			if(count($orderByClauses) > 1){
				throw new Blueberry_Grid_Exception("Multiple-column sorting is
												   not allowed.");
			}

			// Fetch the first column, assume there is only one
			$clause = $orderByClauses[0];

			// Assume format 'columnname ASC/DESC'
			$name = preg_replace('/( ASC| DESC)$/i', '', $clause);
			$direction = preg_match('/( DESC)$/i', $clause) ?
						 Blueberry_Grid_Column::ORDER_DESC :
						 Blueberry_Grid_Column::ORDER_ASC;


		}

		if(strlen($name)){

			$this->getColumnByName($name)->order($direction);
            $this->getColumnByName($name)->setIdentifier($this->identifier);

		}
    }

    protected function setString(){

		$string = '<div class="grid"><table>';

		foreach($this->columns as $column){

			$string .= $column->renderHeader();

		}

		$i = 0;

		while($object = $this->getNextObject()){

			if(!($i % 2)) $class = 'light';
			else $class = 'dark';

			$string .= '<tr class="'.$class.'">';

			foreach($this->columns as $column){

				$string .= $column->renderInRow($object);

			}

			$string .= '</tr>';

		$i++;
		}

		if($this->footer){

			if($this->columns instanceof Blueberry_Collection){

				$colspan = $this->columns->amount();

			} else {

				$colspan = count($this->columns);

			}

			$string .= sprintf('<tr class="footer">
							       <td colspan="%d">%s</td>
								</tr>',
							   $colspan, $this->footer);

		}

		$string .= '</table></div>';

		$this->string = $string;

    }

	public function __toString(){

        return $this->string;

	}
}
