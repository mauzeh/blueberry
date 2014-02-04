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
class Blueberry_Form_Fieldset extends Blueberry_Collection {

	public function __construct(){
	
		foreach(func_get_args() as $a){
			
			if(is_array($a)){
			
				foreach($a as $b) $this->add($b);
			
			} else $this->add($a);
		
		}
		
	}

	public function setForm(Blueberry_Form $form){
	
		$this->form = $form;
		$this->form->addToFlatCollection($this);

		foreach($this->array as $e) if(is_object($e)) $e->setForm($form);
	
	}

	public function add(){

		foreach(func_get_args() as $e){
		
			$this->array[] = $e;
			if(is_object($e) && $this->form != null) $e->setForm($this->form);
		
		}
	}
	
	public function __toString(){

		$s .= '<fieldset>';
	
		foreach($this->array as $a){

			if(is_string($a)){$s .= $a; continue;}
			$s .= $a->__toString();

		}
		
		$s .= '</fieldset>';
		return $s;
		
	}
}
