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
class Blueberry_Form_Radio extends Blueberry_Form_Input {

	protected $checked = false;
	
	public function check(){$this->checked = true;}
	
	public function setValue($value){
	
		if($value == $this->defaultValue) $this->check();

	}
	
	public function __toString(){

		$this->getValue();

		$s .= '<input type="'.$this->type.'" name="'.$this->name.'" ';
		foreach($this->attrs as $key => $value) $s .= $key.'="'.$value.'" ';
		$s .= 'value="'.$this->value.'" class="'.implode(' ',$this->css).'"';
		if($this->checked) $s .= ' checked="checked" ';
		$s .= '/>';
		return $s;
	
	}
}

