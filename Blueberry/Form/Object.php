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
abstract class Blueberry_Form_Object {

	protected $name = '';
	protected $value = '';
	protected $row = null;
	protected $form;
	protected $isError = false;

	public function setForm(Blueberry_Form $form){

		$this->form = $form;
		$this->form->addToFlatCollection($this);

	}

	public function getForm(){

		return $this->form;

	}

	public function setRow($row){

		$this->row = $row;

	}

	public function clearErrors(){

		if(!is_object($this->row)) return;
        if($this->row->getInputFields()->amount() >= 2) return;
        $this->row->clearErrors();

	}

	public function addError($msg){

		if(!$this->row){
            throw new Exception(
                'Sorry, you cannot use a validator without using a Form_Row '.
                'as wrapper around your input fields.'
            );
        }

		$this->row->addError($msg);

	}

	public function getValue(){
		return $this->value;
	}

	// do not call to set default value, use constructor instead
	public function setValue($v){

		if($this->form instanceof Blueberry_Form)
		if($this->form->isPostBack())

		$this->form->setRawDataValue($this->getCleanName(), $v);

		$this->value = $v;

	}

	/**
	 * If <input name="multiple[]"> then strip "[]" and return "multiple"
	 */
	public function getCleanName()
    {
        return preg_replace('/\[.*\]$/', '', $this->name);
    }

	/**
	 * Reformat some_field_name to someFieldName
	 */
	public function getCamelCaseName()
    {
        $names = array();
		foreach(explode('_', $this->getCleanName()) as $i => $name){
            $names[$i] = ucfirst($name);
        }
		return implode('', $names);

	}

	public function getName()
    {
		return $this->name;
	}
}
