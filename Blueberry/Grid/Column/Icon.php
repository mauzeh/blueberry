<?php

/**
 * Places one icon in each row
 */
class Blueberry_Grid_Column_Icon extends Blueberry_Grid_Column {

    protected $src = '';

	/**
	 * @param string $src The full absolute http path to the image icon
	 */
	public function __construct($src)
	{
		$this->src = $src;
	}

	public function renderInRow($data)
	{
		return sprintf('<td><img src="%s" /></td>', $this->src);
	}
}
