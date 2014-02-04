<?php

class Blueberry_Grid_Footer_Element extends Blueberry_Collection {

    const ALIGN_LEFT  = 'alignLeft';
    const ALIGN_RIGHT = 'alignRight';

    protected $classes = array(

        self::ALIGN_LEFT => 'grid-footer-element-left',
        self::ALIGN_RIGHT => 'grid-footer-element-right'

    );

    protected $align = null;
    protected $contents = '';

    /**
     * Constructor.
     *
     * @param string $contents The HTML contents to show in element.
     * @param string $align Where to align the element. Defaults to "left".
     */
    public function __construct($contents, $align = self::ALIGN_LEFT){

        $this->align = $align;
        $this->contents = is_object($contents) ?
                          $contents->__toString() :
                          $contents;

    }

    public function __toString(){

        return sprintf('<div class="%s">%s</div>',
                       $this->classes[$this->align],
                       $this->contents);

    }

}
