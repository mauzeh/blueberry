<?php

/**
 * Modifies the format of a web address so that it's clickable
 */
class Blueberry_Grid_Column_Email extends Blueberry_Grid_Column {

	public function renderInRow($data){

		$name = $this->name;
        $value = $data->$name;
		$text = sprintf(
			'<a href="mailto:%1$s" target="_blank">%1$s</a>', $value
		);

        return $this->renderCell($text);

	}
}
