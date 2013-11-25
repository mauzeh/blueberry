<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Navigation
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents the entire breadcrumbtrail.
 *
 * @package Blueberry_Navigation
 */
class Blueberry_Menu_BreadcrumbTrail extends Blueberry_Collection {

	const SEPARATOR = '&gt;';

	public function __construct(Blueberry_Menu_Item $root){

		$this->add($root);

	}

	public function add(Blueberry_Menu_Item $e){

		$this->array[] = $e;

		if($e->hasActiveChild() && $e->hasChildren()) $this->add($e->getActiveChild());

	}

	public function toHTML(){

		$front = Zend_Controller_Front::getInstance();
		$module = $front->getRequest()->getModuleName();

		$s = '<a href="'.PATH.$module.'">Home</a> '.self::SEPARATOR.' ';

		foreach($this as $e){

			if(!$e->isAllowed()) continue;

			if($e->hasActiveChild()) $s .= $e->toHTML();
			else $s .= $e->label;
			if($e->hasActiveChild()) $s .= ' '.self::SEPARATOR.' ';

		}

		return $s;

	}

	public function __toString(){

		return $this->toHTML();

	}
}
