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
class Blueberry_Form_Fieldset_Title extends Blueberry_Form_Object {

	public function __construct($v){$this->value = $v;}
	
	public function __toString(){return '<div class="fieldset-title">'.$this->value.'</div>';}

}

