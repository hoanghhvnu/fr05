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
			return TRUE;
		} // end if check is digit
		return FALSE;
	} // end setPerPage

	public function getPerPage(){
		if($this->checkReady() == TRUE){
			return $this->_PerPage;
		}
	} // end getPerPage

	public function setBaseUrl($BaseUrl = ''){
		$BaseUrl = trim($BaseUrl);
		if($BaseUrl !== ''){
			$this->_BaseUrl = $BaseUrl;
			return TRUE;
		}
		return FALSE;
	} // end setBaseUrl

	public function getBaseUrl(){
		if($this->checkReady() == TRUE){
			return $this->_BaseUrl;
		}
	} // end getBaseUrl

	public function setTotalItem($TotalItem = ''){
		$TotalItem = trim($TotalItem);
		if( (ctype_digit($TotalItem) == TRUE) && ($TotalItem > 0) ){
			$this->_TotalItem = $TotalItem;
			return TRUE;
		}
		return FALSE;
	} // end setTotalItem
 
	public function getTotalItem(){
		if($this->checkReady() == TRUE){
			return $this->_TotalItem;
		}
	} // end getTotalItem

	private function checkReady(){
		if( (isset($this->_PerPage)) && ($this->_PerPage > 0)
			&& (isset($this->_TotalItem)) && ($this->_TotalItem > 0)
			&& (isset($this->_BaseUrl)) && ($this->_BaseUrl !== '') ){
			if( ($this->_PerPage > $this->_TotalItem)  ){
				$this->_PerPage = $this->_TotalItem;
			} // end if $this->_Perpage
			return TRUE;
		} // end if isset
	} // end checkReady

	public function getNumberPage(){
		if($this->checkReady() == TRUE){
			return $this->_NumberPage;
		}
	} // end getNumberPage

	public function createLink(){
		if($this->checkReady() == TRUE){
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
			} // end if check _NumberPage
		} // end if check ready
	} // end function create link
}// end class pagination
// end file pagination.php