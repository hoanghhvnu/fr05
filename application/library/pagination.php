<?php
/**
 * This class will support for pagination
 */
class pagination{
	private $_PerPage;
	private $_BaseUrl;
	private $_TotalItem;
	private $_NumberPage;
	

	public function setPerPage($Perpage = ''){
		$Perpage = trim($Perpage);
		if( (ctype_digit($Perpage) == TRUE) && ($Perpage > 0) ){
			$this->_PerPage = $Perpage;
		} // end if check is digit
	} // end setPerPage

	public function getPerPage(){
		if( isset($this->_PerPage)){
			return $this->_PerPage;
		}
	}

	public function setBaseUrl($BaseUrl = ''){
		$BaseUrl = trim($BaseUrl);
		if($BaseUrl !== ''){
			$this->_BaseUrl = $BaseUrl;
		}
		
	}

	public function setTotalItem($TotalItem = ''){
		$TotalItem = trim($TotalItem);
		if( (ctype_digit($TotalItem) == TRUE) && ($TotalItem > 0) ){
			$this->_TotalItem = $TotalItem;
		}
	}

	public function checkReady(){
		if( (isset($this->_PerPage)) && ($this->_PerPage > 0)
			&& (isset($this->_TotalItem)) && ($this->_TotalItem > 0)
			&& (isset($this->_BaseUrl)) && ($this->_BaseUrl !== '') ){
			return TRUE;
		}
	}

	public function getNumberPage(){
		if($this->checkReady() == TRUE){
			return $this->_NumberPage;
		}
		
	}


	public function createLink(){
		if($this->checkReady() == TRUE){
			if( ($this->_PerPage > $this->_TotalItem)  ){
				$this->_PerPage = $this->_TotalItem;
			}
			// echo $this->_TotalItem;
			// echo $this->_PerPage;
			// echo $this->_NumberPage;
			$link = '';
			$this->_NumberPage = ceil($this->_TotalItem / $this->_PerPage);
			if($this->_NumberPage > 0){
				for($i = 1; $i <= $this->_NumberPage; $i ++) {
					$link .= "<a ";
					$link .= "href = '";
					$link .= $this->_BaseUrl . '/' . $i;
					$link .= "'>";
					$link .= $i;
					$link .= "</a>  ";
				}
				return $link;
			}
		}
		
		
		
		
	} // end function create link
}// end class
// end file