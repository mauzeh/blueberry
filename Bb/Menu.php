<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Navigation
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents a website menu.
 *
 * @package Blueberry_Navigation
 */
class Blueberry_Menu extends Blueberry_Collection {

	protected $activeElement;
	protected $activeRootElement;

	public function __construct(){

		foreach(func_get_args() as $a){

			if($a instanceof Blueberry_Menu || is_array($a)){

				foreach($a as $e) $this->add($e);

			} elseif($a instanceof Blueberry_Menu_Item){

				$this->add($a);
				
			} elseif(is_object($a)){

				throw new Blueberry_Menu_Exception('Cannot create menu from object of type '.get_class($a));

			} else {

				throw new Blueberry_Menu_Exception('Cannot create menu from input variable of type '.gettype($a));

			}
		}
	}

	public function toHTML($levels = -1, $currentLevel = 0){

		// The two outer divs allow integration with YUI
		//$s .= '<div class="bd"><div><ul>';
		$s = '<ul>';

		foreach($this as $e){

			if($e->isHidden()) continue;

			$s .= '<li';
			if($e->isActive() || $e->hasActiveChild()) $s .= ' class="active"';
			$s .= '>'.$e->toHTML();

			if($levels == -1 || $currentLevel + 1 < $levels){

				if($e->hasChildren()) $s .= $e->getChildren()->toHTML($levels, $currentLevel + 1);

			}

			$s .= '</li>';

		}

		$s .= '</ul>';//</div></div>';

		return $s;

	}

	/**
	 * Create a flat list that shows the hierarchy for this menu
	 */
	public function toFlatListArray($level = 0){

		$a = array();

		foreach($this as $e){

			$a[] = $e;
			// dynamic property
			$e->level = $level;
			if($e->hasChildren()) $a = array_merge($a, $e->getChildren()->toFlatListArray($level + 1));

		}

		return $a;

	}

	public function hasItemsToBeShown(){

		foreach($this as $e) if(!$e->isHidden()) return true;

		return false;

	}

	// requires Blueberry_User
	public function setAccessModel(Zend_Acl $a){

		$this->acl = $a;

		foreach($this as $e) if($e->hasChildren()) $e->getChildren()->setAccessModel($a);

	}

	// requires Blueberry_User extension
	// TODO: show all parents up the tree to prevent unwanted overridden hiddeness
	public function enableAccessRestrictions(Blueberry_User $u){

		foreach($this as $e){

			if(!$u->isAllowed($e->getControllerName(), $e->getActionName())){

				$e->setAllowed(false);
				$e->hide();

			}

			if($e->hasChildren()) $e->getChildren()->enableAccessRestrictions($u);

		}
	}

    /**
     * Fetches the first element it encounters with this href
     *
     * @param string $href The href of the item to search, with trailing slash.
     */
    public function getElementByHref($href){

		foreach($this as $e){

			//p('test '.$href.' against '.$e->href);

			if($e->href == $href){

				return $e;

			} else {

				if($e->hasChildren()){

					$element = $e->getChildren()->getElementByHref($href);

					if($element != false) return $element;

				}
			}
		}

		return false;

	}

	public function getActiveElement(){

		if($this->activeElement instanceof Blueberry_Menu_Item) return $this->activeElement;

		foreach($this as $e){

			if($e->isActive()){

				$this->activeElement = $e;
				return $e;

			} else {

				if($e->hasChildren() && $e->hasActiveChild()){

					$this->activeElement = $e->getChildren()->getActiveElement();
					return $this->activeElement;

				}
			}
		}
	}

	public function getActiveRootElement(){

		if($this->activeRootElement instanceof Blueberry_Menu_Item) return $this->activeRootElement;

		foreach($this as $e){

			if($e->isActive() || $e->hasActiveChild()){

				$this->activeRootElement = $e;
				return $e;

			}
		}
	}
}
