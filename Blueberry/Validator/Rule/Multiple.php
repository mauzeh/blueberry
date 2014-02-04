<?php

class Blueberry_Validator_Rule_Multiple extends Blueberry_Validator_Rule {

	protected $fields = array();

	public function __construct(array $fields, Zend_Validate_Abstract $rule){
	
		$this->fields = $fields;
		$this->rule = $rule;
	
	}

	public function validate(){
	
		if(!$this->validated){
	
			foreach($this->fields as $field){
			
				if(!$field instanceof Blueberry_Form_Object) 
				$field->clearErrors();
				$values[] = $field->getValue();
			
			}
	
			$this->isValid = $this->rule->isValid($values);
			$this->messages = $this->rule->getMessages();
			
			// last field gets error
			foreach($this->messages as $msg) $this->fields[count($this->fields)-1]->addError($msg);

			$this->validated = true;

		}
	}
}

