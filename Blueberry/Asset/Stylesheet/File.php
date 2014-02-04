<?php

class Blueberry_Asset_Stylesheet_File extends Blueberry_Asset_File_Abstract {

	public function __toString(){
	
		return sprintf('<link rel="stylesheet" href="%s" type="text/css" />', $this->path);
	
	}
}

