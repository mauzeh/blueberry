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

	public function setForm($form){
	
		foreach($this->array as $a){
		
			if(is_object($a)) $a->setForm($form);
			
		}
	}

	public function __toString(){
	
		$s .= '<div class="form-column">';
		
		foreach($this->array as $a){
		
			if(!is_object($a)) $s .= $a;
			else $s .= $a->__toString();
			
		}
		
		return $s.'</div>';
		
	}
}

