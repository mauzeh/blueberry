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
class Blueberry_Form extends Blueberry_Collection {

	public $isPostBack = false;
	private $_rawdata = array();
	private $_filter;
	private $action = '';
	private $method = 'post';
	private $encoding = '';
	private $attrs = array();
	protected $validator = null;
	private $onlyShowElements = false;

	protected $dataObject = null;

	/**
	 * For easy storage of field references, as the form
	 * can become quite nested in terms of recursion. This
	 * collection allows us to quickly get a reference to a
	 * form field
	 */
	private $_flatCollection;

	public function __construct(){

		$this->_flatCollection = new Blueberry_Collection();
		$this->array = func_get_args();
		foreach($this->array as $e) if(is_object($e)) $e->setForm($this);
		$this->action = $_SERVER['REQUEST_URI'];

		if($_POST || $_FILES){

			$this->isPostBack = true;
			if(!$this->isEmpty()) $this->loadDataFromArray($_POST);
			$this->_rawdata = $_POST;
			$this->_filter = false;//new Zend_Filter_Input($_POST);

		}
	}

	public function add(){

		foreach(func_get_args() as $e){

			$this->array[] = $e;
			if(is_object($e)) $e->setForm($this);

		}

		if($this->isPostBack()) $this->loadDataFromArray($_POST);

	}

	public function onlyShowElements(){

		$this->onlyShowElements = true;

	}

	public function setRawDataValue($name, $value){

		// check array-support
		$this->_rawdata[$name] = $value;

	}

	public function setValidator(Blueberry_Validator $v){

		$this->validator = $v;

	}

	public function isValid(){

		$this->validator->validate();

		return $this->validator->isValid();

	}

	public function getInputFields(){

		return $this->getElementsByType('Blueberry_Form_Input');

	}

	/**
	 * Also fetches subclasses
	 */
	public function getElementsByType($type){

		$elements = new Blueberry_Collection();

		foreach($this->_flatCollection as $field){

			if($field instanceof $type) $elements->add($field);

		}

		return $elements;

	}

	public function setAttribute($a, $v){$this->attrs[$a] = $v;}

	public function isPostBack(){

		return $this->isPostBack;

	}

	public function isReadyForProcessing(){

		if(!$this->validator) return $this->isPostBack();

		if($this->isPostBack()){

			return $this->isValid();

		} else return false;

	}

	/**
	 * Loads all the form values from an object. Overridden by POST.
	 */
	public function loadDataFromObject($o){

		$this->dataObject = $o;
		$f = $this->getInputFields();

		if(!is_object($o)) throw new Blueberry_Form_Exception(get_class($this).'::loadDataFromObject() expects object as input parameter, '.gettype($o).' given.');
		if($this->isPostBack) return;
		if($f->isEmpty()) return;

		foreach($f as $e){

			$fieldName = $e->getCleanName();

			if($e->getType() == 'submit') continue;
			if(!($fieldName && isset($o->$fieldName))) continue;

			$e->setValue($o->$fieldName);

		}
	}

	/**
	 * Loads all the form values from an array (eg. $_POST).
	 */
	public function loadDataFromArray($array){

		$fields = $this->getInputFields();

		if(!is_array($array)) throw new Blueberry_Form_Exception(get_class($this).'::loadDataFromArray() expects array as input parameter, '.gettype($o).' given.');
		if($fields->isEmpty()) return;

		foreach($fields as $field){

			$fieldName = $field->getCleanName();

			if($field->getType() == 'submit') continue;
			if(!($fieldName && array_key_exists($fieldName, $array))) continue;

			if($field->isArrayLike()){

				// Will be of form "content[sub1][sub2]"
				$name = $field->getName();

				// Will be of form "[content][sub1][sub2]"
				$name = '['.implode('][', explode('[', $name, 2));

				// Will be of form "['content']['sub1']['sub2']"
				$name = str_replace(']', "']", str_replace('[', "['", $name));

                // Will produce a variable $value with the field's submitted
                // value
                eval('$value = $array'.$name.';');

				$field->setValue($value);

			} else {

				$field->setValue($array[$fieldName]);

			}

			$this->setRawDataValue($fieldName, $array[$fieldName]);

		}
	}

	public function setMethod($m){$this->method = $m;}
	public function setAction($m){$this->action = $m;}
	public function getAction(){return $this->action;}

	public function __toString(){

		if(!$this->onlyShowElements){

			$s = '<form class="form" ';
			foreach($this->attrs as $key => $value) $s .= $key.'="'.$value.'" ';
			$s .= ' method="'.$this->method.'" enctype="'.$this->encoding.'" action="'.$this->action.'">';

		}
        
		foreach($this->array as $a){

			if(is_string($a)){$s .= $a; continue;}
			$s .= $a->__toString();

		}

		if(!$this->onlyShowElements){

			$s .= '</form>';

		}

		return $s;

	}

	public function setEncoding($v){$this->encoding = $v;}

	/**
	 * For easy storage of field references, as the form
	 * can become quite nested in terms of recursion. This
	 * collection allows us to quickly get a reference to a
	 * form field (eg. for validation and such).
	 */
	public function addToFlatCollection($e){$this->_flatCollection->add($e);}

	/**
	 * Uses $_flatCollection for easy retrieval of field
	 * references.
	 */
	public function getFieldByName($name){

		foreach($this->_flatCollection as $f){

			if(method_exists($f, 'getName') && $f->getName() == $name) return $f;

		}

		throw new Blueberry_Exception('Unable to retrieve a reference to form field with name "'.$name.'"');

	}

	/**
	 * Uses $_flatCollection for easy retrieval of field
	 * references.
	 */
	public function getFieldsByType($type){

		$fields = new Blueberry_Collection();

		foreach($this->getInputFields() as $f){

			if($f->getType() == $type) $fields->add($f);

		}

		return $fields;

	}

	/**
	 * Fetches the value of a form field. The field name
	 * can also be an array-like name.
	 */
	public function getValue($fieldname, $stripslashes = false){

		$a = explode('[', $fieldname);
		foreach($a as $i => $e) $a[$i] = str_replace(']', '', $e);

		// if the fieldname is array-like
		if(count($a) > 1){

			$value = $this->getValueFromArray($this->_rawdata, $a);

		} else {

			$value = $this->_rawdata[$fieldname];

		}

		if(!get_magic_quotes_gpc() && !is_array($value)){

			if($stripslashes) return $value;
			else return addslashes($value);

		} else {

			if($stripslashes) return stripslashes($value);
			else return $value;

		}

	}

	/**
	 * Alias of getValue()
	 */
	public function get($fieldname, $stripslashes = false){

		return $this->getValue($fieldname, $stripslashes);

	}

	/**
	 * This function fetches the value of a multidimensional array where the
	 * path to the value is another array. Commonly used in forms with
	 * array-like input names.
	 *
	 * Example:
	 * $array['mijnnaam']['klm']['new'] = 'success!';
	 * $path = array('mijnnaam','klm','new');
	 *
	 * echo Form::getValueFromArray($array, $path);
	 *
	 * will return "success!" as string.
	 */
	public function getValueFromArray($array = array(), $pathToValue = array()){

		if(!empty($pathToValue)){

			// get the next available key
			$key = array_shift($pathToValue);
			return $this->getValueFromArray($array[$key], $pathToValue);

		} else {

			// this returns a string, not an array
			return $array;

		}
	}

	public function getRawData(){return $this->_rawdata;}

}
