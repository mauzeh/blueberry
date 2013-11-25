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
class Blueberry_Form_Image extends Blueberry_Form_Input {

	protected $value = 'image';

	public function __construct($src){
	
		$this->src = $src;
	
	}
	
	public function setForm(){
	
		// void, overrides Form_Object::setForm()
		// buttons don't need a reference to the form
	
	}
	
	public function __toString(){
	
		return '<input type="image" class="image" src="'.$this->src.'" />';
	
	}
}

