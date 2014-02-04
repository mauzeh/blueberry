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
	protected $disabled = false;

	// For fields like <input name="option[]" type="checkbox" />
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
		$this->type = strtolower(preg_replace(
            '/Blueberry_Form_/', '', get_class($this)
        ));

		if( ! $attrs['id']){
			$attrs['id'] = sprintf('field_%s', $this->name);
		}

		$this->attrs = $attrs;
		$this->addClass($this->type);

		$this->defaultValue = $defaultValue;
		$this->value = $defaultValue;

	}

	public function setAttribute($key, $value){

        // Overwrite is not allowed. Use the constructor to overwrite.
        if(array_key_exists($key, $this->attrs)){
            throw new Exception(sprintf(
                'Overwriting attribute "%s" for field "%s" is not allowed.',
                $key, $this->name
            ));
        }

        $this->attrs[$key] = $value;
    }

	public function isArrayLike()
    {
		return $this->hasValuesInArray();
	}

    public function disable()
    {
        $this->disabled = true;
    }

    public function enable()
    {
        $this->disabled = false;
    }


	public function hasValuesInArray()
    {
		return $this->valuesInArray;
	}

	public function getType()
    {
		return $this->type;
	}

	public function addClass($class)
    {
        if(empty($this->attrs['class'])){
	        $this->attrs['class'] = $class;
	        return;
        }
	    $this->attrs['class'] .= ' '.$class;
    }

	public function __toString(){

		$this->getValue();

        if($this->disabled){
            $this->attrs['disabled'] = 'disabled';
        }
        $this->attrs['type'] = $this->type;
		if($this->name){
            $this->attrs['name'] = $this->name;
		}
        $this->attrs['value'] = $this->value;

		$s = '<input ';
		foreach($this->attrs as $key => $value)
        {
            $s .= $key.'="'.$value.'" ';
        }
		$s .= '/>';

		return $s;

	}
}
