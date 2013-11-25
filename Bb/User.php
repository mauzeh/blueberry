<?php

class Blueberry_User extends Blueberry_Db_Object {

	protected $acl;
	public $role;
	public $name;
	protected $_name = 'user';

	public function __construct($username, $password = ''){
		if(is_array($username)){
			parent::__construct($username);
		} else {
			parent::__construct(new Blueberry_Db_Query(
				"SELECT * FROM user WHERE username = '%s' AND password = '%s'",
				$username, md5($password)
			));
		}
	}

	public function setAccessModel(Zend_Acl $a){
		$this->acl = $a;
	}

	public function is($role_id){
		return $this->role_id == $role_id;
	}

    public function reloadData(){
        $query = new Blueberry_Db_Query(
            'SELECT * FROM user WHERE id = %d', $this->id
        );
        $this->_data = $query->fetch();
        $this->createFromData();
    }

	public function isAllowed($c, $a = false){
		if(!($this->acl instanceof Zend_Acl)){
			throw new Blueberry_Exception('No Access control list present.');
		}
		// if a resource (controller) is not defined in the acl, always allow it
		if(!$this->acl->has($c)){
			return true;
		}
		return $this->acl->isAllowed($this->role_id, $c, $a);
	}

	public function mayPerformCurrentAction(){
		$front = Zend_Controller_Front::getInstance();
		$c = $front->getRequest()->getControllerName();
		$a = $front->getRequest()->getActionName();
		return $this->isAllowed($c, $a);
	}

	public function isDefault(){
		return $this->role->is_default;
	}
}
