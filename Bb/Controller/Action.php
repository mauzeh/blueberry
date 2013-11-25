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

	public function init(){
		$this->assetManager = Blueberry_Asset_Manager::getInstance();
		$this->_('assets', $this->assetManager);
		$this->_('path', PATH);
		if(array_key_exists('__blueberry_success', $_GET)) $this->triggerInlineSuccess(stripslashes(urldecode($_GET['__blueberry_success'])));
		if(array_key_exists('__blueberry_error', $_GET)) $this->triggerInlineError(stripslashes(urldecode($_GET['__blueberry_error'])));
	}

	public function addCSS($path){
		$this->addFile(new Blueberry_Asset_Stylesheet_File($path));
	}

	public function addJS($path){
		$this->addFile(new Blueberry_Asset_Javascript_File($path));
	}

	public function addFile(Blueberry_Asset_File_Abstract $file){
		if(!($this->assetManager instanceof Blueberry_Asset_Manager)) throw new Blueberry_Exception('Cannot assign file to asset manager. Did you override init() in your action class?');
		$this->assetManager->add($file);
	}

	public function triggerInlineError($msg){
		$this->_('error', $msg);
	}

	public function triggerInlineSuccess($msg){
		$this->_('success', $msg);
	}

	public function triggerSuccess($msg, $url){
		$this->redirect($url.'?__blueberry_success='.urlencode($msg));
		exit;
	}

	public function triggerError($msg, $url){
		$this->redirect($url.'?__blueberry_error='.urlencode($msg));
		exit;
	}

	protected function _($n, $v){
		$this->view->assign($n, $v);
	}
}
