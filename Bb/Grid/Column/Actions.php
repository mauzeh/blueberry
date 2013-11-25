<?php

/**
 * Represents a column that has links to edit or delete the object
 * in the row.
 */
class Blueberry_Grid_Column_Actions extends Blueberry_Grid_Column {

	/**
     * @var array The {@link Blueberry_Grid_Link} objects in this column
     */
	protected $links = array();

	public function __construct(/** variable args **/){

		$this->links = func_get_args();

	}

	public function renderHeader(){

		return '<th></th>';

	}

	/**
	 * Renders the links.
	 *
     * @param object $data The data object of the current row
     */
	public function renderInRow($data){

		$string .= '<td>';
		foreach($this->links as $link) $string .= $link->render($data->id);
		$string .= '</td>';

		return $string;

	}
}


