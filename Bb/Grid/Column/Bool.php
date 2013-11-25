<?php

/**
 * Modifies the format of a bool
 */
class Blueberry_Grid_Column_Bool extends Blueberry_Grid_Column {

	public function renderInRow($data){

		$name = $this->name;
        $value = $data->$name ? 'Yes' : 'No';
		return '<td>'.$value.'</td>';

	}
}
