<?php
class validation extends My_Controller{
	public function isNotEmpty($input = ''){
		$input = trim($input);
		if($input === ''){
			return FALSE;
		}
		return TRUE;
	} // end isEmpty()

	public function isPhone($input){
		if($this->isNotEmpty($input)){
			$InputLength = strlen($input);
			if(ctype_digit($input) && $InputLength >= 9 && $InputLength <= 11)
				return TRUE;
		}
		return FALSE;

	}

	public function isEmail($input){
		if (filter_var($input, FILTER_VALIDATE_EMAIL)){
			return TRUE;
		}
		return FALSE;
	} // end isEmail

	public function isDuplicate($inputName, $inputEmail){
		if($this->isNotEmpty($inputName) && $this->isNotEmpty($inputEmail)){
			$this->loadModel("sinhvienModel");
			$lsSinhvien = $this->model->listSinhvien();
			if(isset($lsSinhvien) && ! empty($lsSinhvien)){
				$ArrayEmail = array();
				$ArrayName = array();
				foreach ($lsSinhvien as $key => $value) {
					$ArrayName[] = $value['sv_name'];
					$ArrayEmail[] = $value['sv_email'];
				}
				if(in_array($inputName, $ArrayName)){
					return 1; // duplicate name
				} else if(in_array($inputEmail,$ArrayEmail)){
					return 2; // duplicate email
				} else{
					return 0;
				}
			} // end isset
		} // end not empty
	} // end isDuplicate
}// end class validation