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
class Blueberry_Form_Container extends Blueberry_Collection {

	private $attributes = array();

	public function __construct(){
	
		foreach(func_get_args() as $a){
			
			if(is_array($a)){
			
				foreach($a as $b) $this->add($b);
			
			} else $this->add($a);
		
		}
	}

	public function setForm($form){
	
		foreach($this->array as $a) if(is_object($a)) $a->setForm($form);
	
	}
	
	public function setAttribute($attribute, $value){
	
		$this->attributes[$attribute] = $value;
	
	}
	
	public function __toString(){
	
		$s .= '<div class="form-container"';
		foreach($this->attributes as $attribute => $value){
		
			$s .= ' '.$attribute.'="'.$value.'"';
		
		}
		
		$s .= '>';

		foreach($this->array as $a){

			if(is_string($a)){$s .= $a; continue;}
			$s .= $a->__toString();

		}
		
		$s .= '</div>';
		
		return $s;
		
	}
}

