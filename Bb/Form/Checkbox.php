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
class Blueberry_Form_Checkbox extends Blueberry_Form_Input {

	protected $type = 'checkbox';
	protected $checked = false;
	protected $disabled = false;

	public function __construct($name, $defaultValue = 1, $attrs = array()){

		parent::__construct($name, $defaultValue, $attrs);

	}

	public function check(){$this->checked = true;}
	public function uncheck(){$this->checked = false;}

	public function disable(){$this->disabled = true;}
	public function enable(){$this->disabled = false;}

	public function setValue($value){

		if($this->valuesInArray && is_array($value)){

			if(in_array($this->defaultValue, $value)) $this->check();

		} else {

			if($value == $this->defaultValue) $this->check();

		}
	}
	
	public function getValue(){
		
		return $this->form->getValue($this->name);
		
	}

	public function __toString(){

		$this->getValue();

		$s .= '<input type="'.$this->type.'" name="'.$this->name.'" ';
		foreach($this->attrs as $key => $value) $s .= $key.'="'.$value.'" ';
		$s .= 'value="'.$this->value.'" class="'.implode(' ',$this->css).'"';
		if($this->checked) $s .= ' checked="checked" ';
		if($this->disabled) $s .= ' disabled="disabled" ';
		$s .= '/>';
		return $s;

	}
}
