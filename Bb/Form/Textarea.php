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
class Blueberry_Form_Textarea extends Blueberry_Form_Input {
	
	public function __toString(){
	
		$this->getValue();
		$s = '<textarea name="'.$this->name.'" id="'.$this->id.'"  class="'.implode(' ',$this->css).'"';
		foreach($this->attrs as $key => $value){
            $s .= $key.'="'.$value.'" ';
        }
		$s .= '>'.$this->value.'</textarea>';
		return $s;
	
	}
}

