<?php

class Blueberry_Validator extends Blueberry_Collection {

	private $validated = false;
	private $isValid = false;

	/**
	 * @param $field can be one single Form_Object or an array of Form_Objects
	 */
	public function addRule($field, Zend_Validate_Abstract $rule){

		if ($field instanceof Blueberry_Form_Object){

			$this->add(new Blueberry_Validator_Rule($field, $rule));

		} elseif (is_array($field)){

			$this->add(new Blueberry_Validator_Rule_Multiple($field, $rule));

		} else throw new Blueberry_Validator_Exception('Validator must be passed instance of Form_Object or array with Form_Objects');

	}

	public function validate(){

		$this->isValid = true;

		foreach($this as $rule){

			if(false === $rule->isValid()){ $this->isValid = false;}

		}

		$this->validated = true;

	}

	public function isValid(){

		$this->validate();
		return $this->isValid;

	}
}