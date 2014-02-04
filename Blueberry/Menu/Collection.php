<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Navigation
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents a collection of website menus.
 *
 * @package Blueberry_Navigation
 */
class Blueberry_Menu_Collection extends Blueberry_Collection {

	public function setAccessModel(Zend_Acl $a){

		foreach($this as $m) $m->setAccessModel($a);

	}

	public function enableAccessRestrictions(Blueberry_User $u){

		foreach($this as $m) $m->enableAccessRestrictions($u);

	}

	/**
	 * First element from first menu
	 */
	public function getActiveRootElement(){

		foreach($this as $m){

			$e = $m->getActiveRootElement();
			if($e instanceof Blueberry_Menu_Item) return $e;

		}
	}

    /**
     * Fetches the first element it encounters with this href
     *
     * @param string $href The href of the item to search, with trailing slash.
     */
    public function getElementByHref($href){

		foreach($this as $m){

			$e = $m->getElementByHref($href);
			if($e instanceof Blueberry_Menu_Item) return $e;

		}
    }

	/**
	 * First element from first menu
	 */
	public function getActiveElement(){

		foreach($this as $m){

			$e = $m->getActiveElement();
			if($e instanceof Blueberry_Menu_Item) return $e;

		}
	}

}
