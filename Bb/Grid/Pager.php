<?php

/**
 * Injects paging functionality for the Grid
 */
class Blueberry_Grid_Pager {

	protected $totalRows = 0;
	protected $totalPages = 0;
	protected $rowsPerPage = 10;
	protected $currentRow = 0;
	protected $currentPage = 0;

    public function __construct()
	{
        $this->setCurrentRow((int)$_GET['__blueberry_grid_pager_start']);
    }

	public function setRowsPerPage($value)
	{
		$this->rowsPerPage = $value;
	}

	public function setTotalRows($value)
	{
		$this->totalRows = $value;
	}

	public function setCurrentRow($value)
	{
		$this->currentRow = $value;
	}

	/**
	 * Rounds down to the lower limit of the current
	 * display interval.
	 *
	 * Example: $this->rowsPerPage = 10, $value = 63
	 * then the number 60 is the correct $this->currentRow.
	 *
	 * Allows direct injection into SQL query
	 */
	protected function correctCurrentRow()
	{
		$this->currentRow = $this->rowsPerPage * floor($this->currentRow / $this->rowsPerPage);
	}

	/**
	 * Returns true if # pages > 1
	 */
	public function isNecessary()
	{
		return $this->totalPages > 1;
	}

	protected function prepareForDisplay(){

		$this->correctCurrentRow();
		$this->currentPage = ($this->currentRow / $this->rowsPerPage) + 1;
		$this->totalPages = ceil($this->totalRows / $this->rowsPerPage);

        $this->urlPrefix = '?';

        foreach($_GET as $key => $value){
			
            // skip to avoid duplicates
            if($key == '__blueberry_grid_pager_start') continue;
			
			if(is_array($value)){

				foreach($value as $subvalue){
				
					$this->urlPrefix .= sprintf('%s=%s&amp;', 
					                            urlencode($key.'[]'), 
												$subvalue);
				
				}
			
			} else {

	            $this->urlPrefix .= sprintf('%s=%s&amp;', $key, $value);		
			
			}
        }
	}

    public function getRowsPerPage(){

        return $this->rowsPerPage;

    }

    public function getCurrentRow(){

        $this->correctCurrentRow();
        return $this->currentRow;

    }

    public function getTotalRows(){

        return $this->totalRows;

    }

	/**
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 * ^-------^     ^------------^     ^-----------------^
	 * Head          Belly              Tail
	 * 
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 *          ^---^              ^---^
	 *          Spacer             Spacer
	 * 
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 * ^ 1                 ^ currentPage = 14           ^ totalPages = 116
	 *
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 *         ^ 1 + headPadding = 5
	 *
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 *               ^ currentPage - bellyPadding = 12
	 *
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 *                           ^ currentPage + bellyPadding = 16
	 *
	 * 1 2 3 4 5 ... 12 13 14 15 16 ... 112 113 114 115 116
	 *                                  ^ totalPages - tailPadding = 112
	 *
	 * Design principles
	 *
	 * -- Belly --
	 * 1. Start with defining the belly (ranging from currentPage - bellyPadding
	 *    to currentPage + bellyPadding). Note that we need to use min() and
	 *    max() to make sure we don't exceed any limits.
	 *
	 * -- Head --
	 * 1. If "left end of belly is smaller or equal to
	 *    1 + headPadding, then extend belly to the left until you reach
	 *    number one.
	 * 2. "left end of belly" is larger than 1 + headPadding, then
	 *    add spacer and add a head from 1 + headPadding back to number one.
	 *
	 * -- Tail --
	 * 1. If min(totalPages, "right end of belly") is larger than or equal to
	 *    totalPages - tailPadding, then extend belly to the right until you
	 *    reach totalPages.
	 * 2. If min(totalPages, "right end of belly") is smaller than
	 *    totalPages - tailPadding, then add spacer and add tail from
	 *    totalPages - tailPadding to totalPages
	 */
	public function __toString(){

		$this->prepareForDisplay();
		
		$this->headPadding = 4;
		$this->bellyPadding = 4; // On each side
		$this->tailPadding = 4;
		
		$bellyLeft = max(1, $this->currentPage - $this->bellyPadding);
		$bellyRight = min(
			$this->totalPages, $this->currentPage + $this->bellyPadding
		);
		
		$belly = range($bellyLeft, $bellyRight);

		$head = range(1, min($this->totalPages, 1 + $this->headPadding));
		$tail = range($this->totalPages, max(
			$this->totalPages, $this->totalPages - $this->tailPadding
		));

		$combined = array_unique(array_merge($head, $belly, $tail));
		
		$pages = array();
		foreach($combined as $page){
			
			if(!$page) continue;
			
			if((int)$previous < $page - 1){
				$pages[] = '...';
			}
			$pages[] = $page;
			$previous = $page;
		}
		
		foreach($pages as $page){
			$row = ($page - 1) * $this->rowsPerPage;
			if($page && $page == '...'){
				$s .= ' ... ';
				continue;
			}
			if($page == $this->currentPage){
				$s .= $page.' ';
			} else {
				$s .= sprintf(
					'<a href="%s__blueberry_grid_pager_start=%s">%s</a> ',
					$this->urlPrefix, $row, $page
				);
			}
		}

		return $s;

	}
}
