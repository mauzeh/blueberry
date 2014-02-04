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
class Blueberry_Form_Row extends Blueberry_Collection {

	const CSS_ROW_ERROR_CLASSNAME = 'form-row form-validation-error-row';
	const CSS_ERROR_CLASSNAME = 'form-validation-error';

	/**
	 * CSS class
	 */
	protected $className = 'form-row';
	protected $errors = array();

	public function add($args = null){

		foreach(func_get_args() as $element){

			if(is_object($element)){

				if(method_exists($element, 'setRow')) $element->setRow($this);
				parent::add($element);

			} elseif(is_string($element)){

				parent::add($element);

			} elseif(is_array($element)){

				foreach($element as $item){
					parent::add($item);
				}

			} else {

				throw new Blueberry_Form_Exception(
                    'Blueberry_Form_Rows can only contain strings or objects'
                );

			}
		}
	}

	public function setClass($css){$this->className = $css;}

	public function setForm(Blueberry_Form $form){

		foreach($this->array as $a){

			if(is_object($a)) $a->setForm($form);

		}
	}

	public function clearErrors(){

		$this->errors = array();
		$this->setClass('form-row');

	}

	public function addError($msg){

        // No use in having the same error message appear more than once.
        // This may happen for instance with date fields that consist of
        // multiple validators (one for each selector). If no value was
        // selected, three identical error messages would.
        if(in_array($msg, $this->errors)){
            return;
        }

		$this->setClass(self::CSS_ROW_ERROR_CLASSNAME);
		$this->errors[] = $msg;

	}

	public function getInputFields(){

		return $this->getElementsByType('Blueberry_Form_Input');

	}

	/**
	 * Also fetches subclasses
	 */
	public function getElementsByType($type){

		$elements = new Blueberry_Collection();

		foreach($this as $field){

			if($field instanceof $type) $elements->add($field);

		}

		return $elements;

	}

	public function __toString(){

		$s = '<div';
		$s .= ' class="'.$this->className.'"';
		$s .= '>';

		foreach($this as $a){

			if(!is_object($a)) $s .= $a;
			else $s .= $a->__toString();

		}

		if(!empty($this->errors)){

			$s .= '<div class="'.self::CSS_ERROR_CLASSNAME.'">'.implode('<br />',$this->errors).'</div>';

		}

		return $s.'</div>';

	}
}


