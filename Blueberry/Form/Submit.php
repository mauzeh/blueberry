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
class Blueberry_Form_Submit extends Blueberry_Form_Input {

	public function __construct($value = '   OK   ')
	{
		parent::__construct('submit', $value);
	}

	/**
	 * Make sure we do not print a name because it might end up in our query.
	 */
	public function __toString()
	{
		unset($this->name);
		return parent::__toString();
	}
}

