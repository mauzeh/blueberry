<?php

class Blueberry_Extension {

	protected $name;
	protected $version = '1.0';

    public function __construct($name, $version = '1.0'){
	
		$this->name = $name;
		$this->version = $version;
	
	}
	
	public function getClassPath(){
	
		return 'extensions/'.str_replace('_', '-', str_replace('blueberry_ext_', '', strtolower($this->name))).'-'.$this->version.'/php/';
	
	}
	
}

