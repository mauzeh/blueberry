<?php

function __autoload($class) {
    $class = 'classes/'.str_replace('\\', '/', $class) . '.php';
	require_once $class;
}