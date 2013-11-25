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
class Blueberry_Form_Label extends Blueberry_Form_Object {

	public function __construct($value, $showColon = true){
	
		$this->value = $value;
		$this->showColon = $showColon;
	
	}

	public function setForm(){
	
		// void, overrides Form_Object::setForm()
		// labels don't need a reference to the form
	
	}
	
	public function __toString(){

		return '<label>'.$this->value.($this->showColon ? ':' : '').'</label>';
	
	}
}
