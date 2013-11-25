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
class Blueberry_Form_Select extends Blueberry_Form_Input {

	private $elements;
	private $valueLabel;

	/**
	 * @param string $name the name of the element
	 * @param Blueberry_Collection $elements the elements in the drop down
	 * @param string $valueLabel the string in the value="" part (value="id" is default)
	 * @param string $defaultValue the selected value (overridden by POST)
	 */
	public function __construct($name, $elements = null, $valueLabel = 'id', $defaultValue = ''){

		$this->name = $name;
		$this->elements = new Blueberry_Collection();

		if(is_array($elements)){

			foreach($elements as $value => $text) $this->addOption($value, $text);

		} elseif($elements instanceof Blueberry_Db_Query){

			while($r = $elements->fetch()){

				// ill-defined query
				if(!array_key_exists('value', $r) || !array_key_exists('key', $r)){
					throw new Blueberry_Form_Select_Exception('Cannot create field from ill-defined query. Make sure your query creates two output columns: "key" and "value".');
				}

				$this->addOption($r['key'], $r['value']);

			}

		} elseif($elements instanceof Blueberry_Collection){

			$this->elements = $elements;

		}

		$this->valueLabel = $valueLabel;
		$this->value = $defaultValue;
		$this->css[] = 'select';

	}

	public function addOptions(Blueberry_Collection $c){

		if(!($this->elements instanceof Blueberry_Collection)) throw new Blueberry_Form_Select_Exception('$this->elements is not a Collection');

		if(!$this->elements->isEmpty()){

			foreach($c as $e) $this->elements->add($e);

		} else {

			$this->elements = $c;

		}
	}

	public function addOption($value, $text = null){

		if(is_object($value)){

			$this->elements->add($value);

		// object
		} else {

			if($text === null) $text = preg_replace('/_/', ' ', ucfirst(strtolower($value)));
			$this->elements->add(new Blueberry_Form_Select_Option($value, $text));

		}
	}

	public function __toString(){

		$s .= '<select name="'.$this->name.'" class="'.implode(' ',$this->css).'"';
		foreach($this->attrs as $key => $value) $s .= $key.'="'.$value.'" ';
		if($this->disabled) $s .= ' disabled="disabled"';
		$s .= '>';
		$valueLabel = $this->valueLabel;
		foreach($this->elements as $o){

			if($o instanceof Blueberry_Form_Select_Option){

				$value = $o->id;
				$text = $o->text;

			} else {

				$value = $o->$valueLabel == '' ? $o : $o->$valueLabel;
				$text = $o;

			}

			$s .= '<option value="'.$value.'"';
			if($value == $this->value) $s .= ' selected="selected"';
			$s .= '>'.$text.'</option>';

		}

		$s .= '</select>';
		return $s;

	}
}
