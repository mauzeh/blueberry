<?php

class Blueberry_Grid_Footer extends Blueberry_Collection {

    public function __toString(){

        foreach($this as $e) $string .= $e->__toString();

        return $string;

    }

}
