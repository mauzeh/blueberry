<?php

class Blueberry_Validator_Rule {

	protected $validated = false;

	public function __construct(Blueberry_Form_Object $f, Zend_Validate_Abstract $r){
		
		// Checkboxes are currently not supported because of getValue().
		/*if(is_a($f, 'Blueberry_Form_Checkbox')){
			throw new Blueberry_Form_Exception(
				'Checkboxes cannot be validated.');
		}*/

		$this->field = $f;
		$this->rule = $r;

	}

	public function validate(){

		if(!$this->validated){

			$this->field->clearErrors();
			$this->isValid = $this->rule->isValid($this->field->getValue());
			$this->messages = $this->rule->getMessages();
			foreach($this->messages as $msg) $this->field->addError($msg);
			$this->validated = true;

		}
	}

	public function isValid(){

		$this->validate();
		return $this->isValid;

	}
}