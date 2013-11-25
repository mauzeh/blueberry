<?php

/**
 * Modifies the format of a MySQL date column
 */
class Blueberry_Grid_Column_Date_MySQL extends Blueberry_Grid_Column {

	public function renderInRow($data){

		$name = $this->name;
        $date = new Zend_Date($data->$name, Zend_Date::ISO_8601);

        return $this->renderCell(ucfirst($date->get(Zend_Date::DATE_FULL)));

	}
}
