<?php

/**
 * Modifies the format of a MySQL datetime column
 */
class Blueberry_Grid_Column_Timestamp_Formatted extends Blueberry_Grid_Column {

	protected $strftime_format;

	public function __construct(
		$name, $label = '', $strftime_format = '%a %e %b %Y %H:%M'
	){
		parent::__construct($name, $label);
		$this->strftime_format = $strftime_format;
	}

	public function renderInRow($data){

        return $this->renderCell(strftime(
	        $this->strftime_format, $data->{$this->name}
        ));
	}
}
