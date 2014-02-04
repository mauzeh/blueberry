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

    protected $for = '';

	public function __construct($value, $showColon = true, $for = ''){
	
		$this->value = $value;
		$this->showColon = $showColon;
        $this->for = $for;
	
	}

	public function setForm(){
	
		// void, overrides Form_Object::setForm()
		// labels don't need a reference to the form
	
	}
	
	public function __toString(){

        $text = $this->value.($this->showColon ? ':' : '');

		return sprintf('<label for="%s">%s</label>', $this->for, $text);
	
	}
}
