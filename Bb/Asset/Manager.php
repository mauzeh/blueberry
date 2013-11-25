<?php

/**
 * Singleton class
 */
class Blueberry_Asset_Manager extends Blueberry_Collection {

    /**
     * Singleton instance
     *
     * @var Blueberry_Extension_Manager
     */
    protected static $_instance = null;

    /**
     * Singleton instance
     *
     * @return Zend_Controller_Front
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

	public function __toString(){

		return implode("\n", $this->array);

	}

}
