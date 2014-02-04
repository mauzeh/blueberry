<?php

class Blueberry_Asset_Javascript_File extends Blueberry_Asset_File_Abstract {

	public function __toString(){
	
		return sprintf('<script type="text/javascript" src="%s"></script>', $this->path);
	
	}
}

