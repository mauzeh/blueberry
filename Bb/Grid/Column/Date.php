<?php

/**
 * Modifies the format of a date attribute
 */
class Blueberry_Grid_Column_Date extends Blueberry_Grid_Column {

	public function renderInRow($data){

		$name = $this->name;
        $date = $data->$name;

        return $this->renderCell(ucfirst($date->get(Zend_Date::DATE_FULL)));

	}
}
