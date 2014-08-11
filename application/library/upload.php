<?php
class upload{
	public function __construct(){

	} // end __construct



	public function checkTypeUpload($FileInfoUpload = array(), $validType = ''){
		if(! empty($FileInfoUpload) && $validType !== ''){
			$temp = explode("/", $FileInfoUpload['type']);
			if(! empty($temp)){
				$FileType = array_shift($temp);
				if($FileType == $validType){
					return TRUE;
				}
				return FALSE;
			}
			
			// $FileType = array_shift($FileType);
			
		}
		
	} // end checkTypeUpload

	public function checkSizeUpload($FileInfoUpload = array(), $maxSizeInKb = ""){
		if(! empty($FileInfoUpload) && $maxSizeInKb !== ''){
			$FileSize = $FileInfoUpload['size'];
			// $FileSize = array_shift($FileSize);
			// echo $FileSize;
			if( ($FileSize / 1024) < $maxSizeInKb ){
				return TRUE;
			}
			return FALSE;
		}
		
	} // end checkTypeUpload

	public function doUpload($FileInfoUpload = array(), $DesDir = ''){
		if(! empty($FileInfoUpload) && $DesDir !== ''){
			$NewName = time() . $FileInfoUpload['name'];
			// echo $DesDir . "/" . $NewName;
			move_uploaded_file($FileInfoUpload['tmp_name'], $DesDir . "/" . $NewName);
			return $NewName;
		}
	}
} // end class upload
// end file upload.php