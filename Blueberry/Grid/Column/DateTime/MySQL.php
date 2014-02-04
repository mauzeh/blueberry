<?php

/**
 * Modifies the format of a MySQL datetime column
 */
class Blueberry_Grid_Column_DateTime_MySQL extends Blueberry_Grid_Column {

	public function renderInRow($data){

		$name = $this->name;
        $date = strtotime($data->$name);

        return $this->renderCell(strftime(
	        '%A %e %h %Y %H:%M', $date
        ));
	}
}
