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
class Blueberry_Form_Column extends Blueberry_Collection {

	public function __construct()
	{
		// Normally, args are add()-ed to the collection. If I pass an array
		// then the array will be the collection's only element. We don't want
		// this behavior for Form_Columns because we want to quickly build
		// a column with an array of fields.
		$args = func_get_args();
		if(count($args) == 1 && is_array($args[0])){
			foreach($args[0] as $element){
				$this->add($element);
			}
		} else {
			// In all other cases, fall back to the parent constructor.
			call_user_func_array(array('parent', '__construct'), $args);
		}
	}

	public function setForm($form){
	
		foreach($this->array as $a){
		
			if(is_object($a)) $a->setForm($form);
			
		}
	}

	public function __toString()
	{
		$s = '<div class="form-column">';
		foreach($this->array as $a){
			if(!is_object($a)) $s .= $a;
			else $s .= $a->__toString();
		}
		return $s.'</div>';
	}
}

