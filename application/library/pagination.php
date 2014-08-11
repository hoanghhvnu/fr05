<?php
/**
 * This class will support for pagination
 */
class pagination{
	private $_PerPage;
	private $_BaseUrl;
	private $_TotalItem;

	public function setPerPage($Perpage){
		$this->_PerPage = $Perpage;
	}

	public function setBaseUrl($BaseUrl){
		$this->_BaseUrl = $BaseUrl;
	}

	public function setTotalItem($TotalItem){
		$this->_TotalItem = $TotalItem;
	}

	public function createLink(){
		$link = '';
		$numPage = ceil($this->_TotalItem / $this->_PerPage);
		if($numPage > 0){
			for($i = 1; $i <= $numPage; $i ++) {
				$link .= "<a ";
				$link .= "href = '";
				$link .= $this->_BaseUrl . '/' . $i;
				$link .= "'>";
				$link .= $i;
				$link .= "</a>  ";
			}
			return $link;
		}
		
	} // end function create link
}// end class
// end file