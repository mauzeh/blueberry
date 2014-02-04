<?php

class Blueberry_Grid_Column {

	protected $name;
	protected $label;
    protected $orderable = true;
    protected $isOrdered = false;
    protected $orderDirection = self::ORDER_ASC;
    protected $grid = null;
    protected $identifier = '';

    protected $orderClasses = array(
        self::ORDER_DESC => 'order-desc',
        self::ORDER_ASC  => 'order-asc'
    );

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

	/**
	 * @param string $name The name of the object attribute in this column
	 * @param string $label The column label (optional), defaults to a
	 *  capitalized $name.
	 */
	public function __construct($name, $label = null)
	{
		$this->name  = $name;
		$this->label = $label;

		if($this->label == null){

			$this->label = implode(
				' ', array_map('ucfirst', explode('_', $name))
			);
		}
	}

    /**
     * Will be called by the Grid if necessary
     *
     * @param string $direction Either self::ORDER_ASC or self::ORDER_DESC
     */
    public function order($direction)
    {
        $this->isOrdered = true;
        $this->orderDirection = $direction;
    }

    /**
     * Determines if the column is ordered (ORDER BY).
     *
     * Cannot be called before the grid is instantiated because only from then
     * on will $this->grid be defined.
     */
    public function isOrdered(){

        return $this->isOrdered;

    }

	public function renderHeader(){

        if(!$this->orderable){

            return sprintf('<th class="%s">%s</th>', $this->name, $this->label);

        }

        if($this->isOrdered()){

            $class = $this->orderClasses[$this->orderDirection];

        }

        $url = '?';

        $urlVariables = $_GET;

        // Avoid duplicates
        $urlVariables['__blueberry_grid_'.$this->identifier.'_orderby'] = $this->name;
        $urlVariables['__blueberry_grid_'.$this->identifier.'_orderdirection'] =
            $this->orderDirection == self::ORDER_ASC && $this->isOrdered() ?
                                     self::ORDER_DESC :
                                     self::ORDER_ASC;

        foreach($urlVariables as $key => $value){

            $url .= sprintf('%s=%s&amp;', $key, $value);

        }

		return sprintf(
			'<th class="%s"><a href="%s" class="%s">%s</a></th>',
			$this->name, $url, $class, $this->label
		);
	}

    /**
     * Fetches the default value for the column by using the column name
     * as key of the object.
     */
    protected function getDefaultValue($data)
    {
		$name = $this->name;
		return $data->$name;
    }

    public function setIdentifier($identifier){

        $this->identifier = $identifier;

    }

    public function getName()
    {
        return $this->name;
    }

    public function renderCell($text, $class = '')
    {
        if($class == ''){
            $class = $this->isOrdered() ? 'ordered' : '';
        }
	    $class .= ' column-'.$this->name;
        return sprintf('<td class="%s">%s</td>', $class, $text);
    }

	public function renderInRow($data)
	{
		$value = $this->getDefaultValue($data);

		if($value != ''){

            $text = $value;
            $class = $this->isOrdered() ? 'ordered' : '';


		} else {

            $class = 'empty';
            $text = '-';

		}

        return $this->renderCell($text, $class);
	}
}
