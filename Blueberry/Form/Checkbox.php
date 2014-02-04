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

	protected $checked = false;

	public function __construct($name, $value = '1', $attrs = array())
    {
	    $this->name = $name;

	    // We need to overwrite the id because checkboxes/radios have the same
	    // name which would make the id non-unique (causing e.g. <label for=id>
	    // to fail).
	    if( ! $attrs['id']){
		    $attrs['id'] = sprintf('field_%s_%s', $this->name, $value);
	    }

		parent::__construct($name, $value, $attrs);
	}

	public function check()
    {
        $this->checked = true;
    }

	public function uncheck()
    {
        $this->checked = false;
    }

	public function setValue($value)
    {
		if($this->valuesInArray && is_array($value)){

			if(in_array($this->defaultValue, $value)) $this->check();

		} else {

			if($value == $this->defaultValue) $this->check();

		}
	}
	
	public function getValue()
    {
		return $this->form->getValue($this->name);
	}

	public function __toString(){
        if($this->checked){
            $this->attrs['checked'] = 'checked';
        }
        return parent::__toString();
	}
}
