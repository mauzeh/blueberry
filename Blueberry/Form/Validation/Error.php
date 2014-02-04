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
class Blueberry_Form_Validation_Error extends Blueberry_Form_Object {

	protected $fieldName;
	protected $message = 's';
	
	public function __construct($f){
	
		$this->fieldName = $f;
		
	}
	
	public function __toString(){
	
		$s = '<div class="bb-notice inline-validation-error">';
		$s .= $this->message;
		$s .= '</div>';
		
		return $s;
	
	}

}

