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
class Blueberry_Form_Submit extends Blueberry_Form_Input {

	protected $value = 'Submit';

	public function __construct($value = 'Submit Query'){
	
		$this->value = $value;
		$this->type = 'submit';
	
	}
	
	public function __toString(){
	
		return '<input type="submit" class="submit" value="'.$this->value.'"  class="'.implode(' ',$this->css).'" />';
	
	}
}

