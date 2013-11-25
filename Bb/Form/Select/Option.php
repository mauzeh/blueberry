<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Form
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * @tutorial Blueberry_Form.pkg
 * @package Blueberry_Form
 */
class Blueberry_Form_Select_Option {

	public $id;
	public $text;
	
	public function __construct($id, $text){
	
		$this->id = $id;
		$this->text = (string)$text;
	
	}
	
	public function __toString(){return $this->text;}

}

