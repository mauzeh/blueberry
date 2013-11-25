<?php

/**
 * The default error handler plugin in ZF 1.0.2 does not allow
 * the module in which the error is displayed to be dynamically determined.
 * Let's just hope they change this in the future...
 */
class Blueberry_Controller_Plugin_ErrorHandler extends Zend_Controller_Plugin_ErrorHandler {

    public function postDispatch(Zend_Controller_Request_Abstract $request){

		// set the modulename
		$front = Zend_Controller_Front::getInstance();
		$front->setParam('noErrorHandler', false);
		$this->setErrorHandlerModule($front->getRequest()->getModuleName());
		parent::postDispatch($request);
	
	}
}

