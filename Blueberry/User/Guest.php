<?php

class Blueberry_User_Guest extends Blueberry_User {

	public function __construct(){
	
		$this->role = new Blueberry_User_Role(1);
		$this->name = 'Guest';
	
	}
}

