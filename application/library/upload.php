<?php
class upload{
	public function __construct(){

	} // end __construct

	public createInputFile(){

	} // end createInputFile

	public checkTypeUpload($FileNameUpload = ''){
		if($FileNameUpload !== ''){
			$FileType = array_shift(explode("/", $FileNameUpload));
			// $FileType = array_shift($FileType);
			echo $FileType;
		}
		
	} // end checkTypeUpload
} // end class upload
// end file upload.php