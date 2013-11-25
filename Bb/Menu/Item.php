<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_Navigation
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * Represents a website menu item.
 *
 * @package Blueberry_Navigation
 */
class Blueberry_Menu_Item {

	public $label;
	public $href;
	public $querystring;
	protected $hidden = false;
	protected $children;
    protected $allowed = true;
	public $active = false;

	public function __construct($href, $label, Blueberry_Menu $children = null){
		$components = parse_url($href);
		$this->href = $components['path'];
		$this->querystring = $components['query'];
		$this->label = $label;
		$this->children = $children;
		$this->init();
	}

	protected function init(){
		$this->makeActive();
		if($this->children == null) $this->children = new Blueberry_Menu();
	}

	protected function makeActive(){
		$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		$p = $params['module'].'/'.$params['controller'].'/'.$params['action'].'/';
		$this->active = strtolower($this->href) === strtolower($p);
	}

    public function setAllowed($bool){
        $this->allowed = $bool;
    }

    public function isAllowed(){
        return $this->allowed;
    }

    /**
     * Activates the item. Overrules anything done previously.
     */
    public function activate(){
        $this->active = true;
    }

	public function getControllerName(){
		$f = preg_replace('/\/$/','',$this->href);
		$controller = explode('/', $f);
		return $controller[count($controller) - 2];
	}

	public function getActionName(){
		$f = preg_replace('/\/$/','',$this->href);
		$action = explode('/', $f);
		return $action[count($action) - 1];
	}

	public function getModuleName(){
		$f = preg_replace('/\/$/','',$this->href);
		$module = explode('/', $f);
		return $module[count($module) - 3];
	}

	public function hasActiveChild(){
		if(!$this->hasChildren()) return false;
		foreach($this->children as $child) if($child->hasActiveChild() || $child->isActive()) return true;
	}

	public function getActiveChild(){
		if($this->hasChildren()){
			foreach($this->children as $child) if($child->hasActiveChild() || $child->isActive()) return $child;
		} else return false;
	}

	public function isActive(){
		return $this->active;
	}

	public function isHidden(){
		return $this->hidden;
	}

	public function getChildren(){
		return $this->children;
	}

	public function hasChildren(){
		return $this->children != null;
	}

	public function hide(){
		$this->hidden = true;
	}

	public function show(){
		$this->hidden = false;
	}

	public function toHTML(){
		$url = PATH.$this->href;
		if($this->querystring){
			$url .= '?'.$this->querystring;
		}
		return sprintf('<a href="%s">%s</a>', $url, $this->label);
	}

	public function __toString(){
		return $this->label;
	}
}
