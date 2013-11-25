<?php

abstract class Blueberry_Asset_File_Abstract {

	protected $path;
	
	public function __construct($path){
	
		$this->path = $path;
	
	}
}

