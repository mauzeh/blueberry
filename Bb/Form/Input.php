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
abstract class Blueberry_Form_Input extends Blueberry_Form_Object {

	protected $type = null;
	protected $attrs = array();
	protected $css = array();
	protected $disabled = false;

	// for fields like <input name="option[]" type="checkbox" />
	protected $valuesInArray = false;

	protected $defaultValue = '';

	/**
	 * @param string $name the name of the element
	 * @param strind $id the id of the element
	 * @param string $defaultValue the selected value (overridden by POST)
	 * @param array $attrs the HTML attributes of the element
	 */
	public function __construct($name, $defaultValue = '', $attrs = array()){

		$this->name = $name;
		if(preg_match('/\[.*\]$/', $this->name)) $this->valuesInArray = true;
		$this->type = strtolower(preg_replace('/Blueberry_Form_/', '', get_class($this)));
		$this->css[] = $this->type;
		foreach($attrs as $key => $value) $this->attrs[$key] = $value;
		$this->defaultValue = $defaultValue;
		$this->value = $defaultValue;

	}

	public function setAttribute($a, $v){$this->attrs[$a] = $v;}

	public function isArrayLike(){

		return $this->hasValuesInArray();

	}

    public function disable(){

        $this->disabled = true;

    }

	public function hasValuesInArray(){

		return $this->valuesInArray;

	}

	public function getType(){

		return $this->type;

	}

	public function addClass($c){$this->css[] = $c;}

	public function __toString(){

		$this->getValue();
		$s = '<input type="'.$this->type.'" name="'.$this->name.'" ';
		if($this->disabled) $s .= ' disabled="disabled"';
		foreach($this->attrs as $key => $value) $s .= $key.'="'.$value.'" ';
		$s .= 'value="'.$this->value.'" class="'.implode(' ',$this->css).'" ';
		$s .= '/>';

		return $s;

	}
}
