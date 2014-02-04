<?php

class Blueberry_View_Smarty extends Zend_View_Abstract {

    private $smarty;
    
    public function __construct($config = array())
	{
        parent::__construct($config);
		require_once 'Smarty/Smarty.class.php';
        $this->smarty = new Smarty();
    }
	
	public function assign($var, $value = null)
	{
		if (is_string($var)) {
			$this->smarty->assign($var, $value);
		} elseif (is_array($var)) {
			foreach ($var as $key => $value){
				$this->smarty->assign($key, $value);
			}
		} else throw new Zend_View_Exception('Blueberry_View_Smarty::assign() expects a string or array, got '.gettype($var));
	}
	
	public function render($name)
	{
		$this->assign('notices', Blueberry_Notice::detect());
		$front = Zend_Controller_Front::getInstance();
		$this->smarty->template_dir = 'application/modules/'.$front->getRequest()->getModuleName().'/views/scripts/';
		$this->smarty->compile_dir = 'application/modules/'.$front->getRequest()->getModuleName().'/views/scripts_c/';
		$this->smarty->display($name);
	}
	
	public function _run(){}
	
}

