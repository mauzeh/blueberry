<?php

/**
 * Blueberry Framework
 *
 * @category   Blueberry
 * @package    Blueberry_MVC
 * @copyright  Copyright (c) 2007-2008 Bluedackers (http://www.bluedackers.com)
 */

/**
 * A custom implementation of Zend_Controller_Action.
 *
 * @package    Blueberry_MVC
 */
abstract class Blueberry_Controller_Action extends Zend_Controller_Action {

	public function init()
	{
		$this->assetManager = Blueberry_Asset_Manager::getInstance();
		$this->_('assets', $this->assetManager);
		$this->_('path', PATH);
	}

	public function triggerError($message, $redirect = '')
	{
		$this->flash('error', $message);
		if($redirect){
			$this->redirect($redirect);
		}
	}

	public function triggerSuccess($message, $redirect = '')
	{
		$this->flash('success', $message);
		if($redirect){
			$this->redirect($redirect);
		}
	}

	public function flash($type, $title, $message = '')
	{
		Blueberry_Notice::raise($type, $title, $message);
	}

	public function addCSS($path)
	{
		$this->addFile(new Blueberry_Asset_Stylesheet_File($path));
	}

	public function addJS($path)
	{
		$this->addFile(new Blueberry_Asset_Javascript_File($path));
	}

	public function addFile(Blueberry_Asset_File_Abstract $file){
		if(!($this->assetManager instanceof Blueberry_Asset_Manager)){
            throw new Blueberry_Exception(
                'Cannot assign file to asset manager. Did you override init() '.
                'in your action class?'
            );
        }
		$this->assetManager->add($file);
	}

	protected function _($n, $v){
		$this->view->assign($n, $v);
	}
}
