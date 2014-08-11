<?php
// require('config/database.php');
class sinhvienModel extends database{
	private $_table = 'tbl_sinhvien';
	public function __construct(){
		// parent::__construct();
		$this->connect();
	}

	public function detailSinhvien($id){
		$sql = "SELECT * FROM $this->_table";
		$sql .= " WHERE sv_id = $id";
		$this->query($sql);
		return $this->fetch();
	} // end detailSinhvien

	public function totalRows(){
		$sql = "SELECT * FROM $this->_table";
		$this->query($sql);
		return $this->numRows();
	}

	public function validEmail($email = '', $id = 0){
		$sql = "SELECT * FROM $this->_table";
		if($email !== ''){
			if($id == 0){
				$sql .= " WHERE sv_email ='" . $email . "'";
			}else{
				$sql .= " WHERE sv_email ='" . $email . "' AND sv_id != '" . $id . "'";
			}
			$this->query($sql);
			$numEmail = $this->numRows();
			if($numEmail > 0){
				return FALSE;
			}
			return TRUE;
		} // end if check empty
	} // end checkEmail

	public function validName($name = '', $id = 0){
		$sql = "SELECT * FROM $this->_table";
		if($name !== ''){
			if($id == 0){
				$sql .= " WHERE sv_name ='" . $name . "'";
			}else{
				$sql .= " WHERE sv_name ='" . $name . "' AND sv_id != '" . $id . "'";
			}
			$this->query($sql);
			$numName =  $this->numRows();
			if($numName > 0){
				return FALSE;
			}
			return TRUE;
		} // end if check empty
	} // end checkEmail


	public function listSinhvien($start = '', $limit = ''){
		$sql = "SELECT * FROM $this->_table";
		if($start !== '' && $limit !== ''){
			$sql .= " limit " . $start . "," . $limit;
		}
		// echo $sql;
		$this->query($sql);
		return $this->fetchAll();

	} // end listSinhvien()

	public function deleteSinhvien($ArrayDeleteId = array()){
		if( ! empty($ArrayDeleteId)){
			$StringDeleteId = "(" . implode(",", $ArrayDeleteId) . ")";
			$sql = "DELETE FROM $this->_table WHERE sv_id IN $StringDeleteId";
			// echo $sql;
			$this->query($sql);
			return TRUE;
		}
		return FALSE;
	} // end funcion deleteSinhvien()

	public function insertSinhvien($SinhvienInfo = array()){
		// echo __METHOD__;
		if(empty($SinhvienInfo)){
			return FALSE;
		} else{
			foreach ($SinhvienInfo as $key => $value) {
				$SinhvienInfo[$key] = "'" . $value . "'";
			} // end foreach
			$StringInsert = implode(",", $SinhvienInfo);

			$sql = "INSERT INTO $this->_table (sv_name,sv_email,sv_info,sv_address,sv_phone,sv_school,sv_avata,sv_gender) ";
			$sql .= " VALUES (";
			$sql .= $StringInsert;
			$sql .= ")";
			// echo '___';
			// echo $sql;
			$this->query($sql);
			return TRUE;
		}
	}

	public function editSinhvien($SinhvienInfo = array(), $id){
		if(empty($SinhvienInfo)){
			return FALSE;
		} else{
			foreach ($SinhvienInfo as $key => $value) {
				$SinhvienInfo[$key] = "'" . $value . "'";
			} // end foreach
			$ArrayColumnValue = array();
			foreach ($SinhvienInfo as $key => $value) {
				$ArrayColumnValue[$key] = $key . "=" . $value;
			}
			$StringUpdate = implode(",", $ArrayColumnValue);
			// echo $StringUpdate;

			$sql = "UPDATE $this->_table SET ";
			$sql .= $StringUpdate;
			$sql .= " WHERE sv_id = $id";
			echo $sql;
			$this->query($sql);
			// return TRUE;
		}

	}
} // end class sinhvienModel
// end file sinhvienModel.php