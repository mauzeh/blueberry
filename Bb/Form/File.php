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
class Blueberry_Form_File extends Blueberry_Form_Input {

	public function setForm($form){

		parent::setForm($form);
		
		// this is a file upload field so we must change the form's enctype
		$this->form->setEncoding('multipart/form-data');

	}
}

