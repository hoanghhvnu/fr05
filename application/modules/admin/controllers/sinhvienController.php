<?php
// require ('BaseController.php');
class sinhvienController extends My_Controller{
	protected $_error = array();

	public function __construct(){
		$this->loadModel("sinhvienModel");
		$this->loadLibrary("validation");
		// session_start();
	}
	
	public function indexAction(){
		// $this->loadModel("sinhvienModel");
		if(isset($_GET['id'])){
			$page = $_GET['id'];
			// echo $page;
		} else{
			$page = 1;
		}
		$Perpage = 5;
		$TotalRows = $this->model->totalRows();
		$NumPage = ceil($TotalRows / $Perpage);
		$start =( $page - 1 ) * $Perpage;
		$url = $this->baseurl("/admin/sinhvienController/indexAction/");
		$link = "";
		if($NumPage > 0){
			for($i = 1; $i <= $NumPage; $i ++){
				$link .= "<a ";
				$link .= "href = " . $url . $i;
				$link .= ">";
				$link .= $i . "</a>";
				$link .= " ";
			}
		}
		$data['link'] = $link;
		$data['lsSinhvien'] = $this->model->listSinhvien($start, $Perpage);
		// echo "<pre>";
		// print_r($data);
		$this->loadView('listSinhvien',$data);
	} // end indexAction

	public function insertAction(){
		$params = $_REQUEST;
		$data = array();
		// print_r($params);
		if(isset($_POST['btnok'])){
			if($this->checkInputData($params)){
				$SinhvienInsert = array(
					'sv_name'    => $params['txtname'],
					'sv_email'   => $params['txtemail'],
					'sv_info'    => $params['txtinfo'],
					'sv_address' => $params['txtaddress'],
					'sv_phone'   => $params['txtphone'],
					'sv_school'  => $params['txtschool'],
					'sv_avata'   => $params['txtavata'],
					'sv_gender'  => $params['gender']
					);
				$data['detailSinhvien'] = $SinhvienInsert;
				// $checkDuplicate = $this->library->isDuplicate($params['txtname'], $params['txtemail']);
				$numEmail = $this->model->checkEmail($params['txtemail']);
				// echo $numEmail;
				// return;
				if($numEmail > 0){
					$this->_error['errorEmail'] = "Email đã tồn tại"; 
				}
				// if($checkDuplicate == 0){
				// 	$this->model->insertSinhvien($SinhvienInsert);
				// 	$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
				// } else if($checkDuplicate == 1){
				// 	$this->_error['errorName'] = "Tên sinh viên đã tồn tại"; 
				// } else if($checkDuplicate == 2){
				// 	$this->_error['errorEmail'] = "Email đã tồn tại"; 
				// }
			} else{
				$SinhvienInsert = array();
				if(isset($params['txtname']) && $params['txtname'] != ''){
					$SinhvienInsert['sv_name'] = $params['txtname'];
				}
				if(! empty($SinhvienInsert)){
					$data['detailSinhvien'] = $SinhvienInsert;
				}
				
			}
		} // end if submit
		// $data['ek'] = 3;
		// print_r($data);
		// print_r($data);
		// $oldData = $data;
		// $data = array_merge($oldData, $this->_error);
		// echo "<pre>";
		// print_r($data);
		if(isset($data) && ! empty($data)){
			$oldData = $data;
			$data = array_merge($this->_error,$oldData);
		} else{
			$data = $this->_error;
		}
		
		// echo "<pre>";
		// print_r($data);
		$this->loadView('insertSinhvien',$data);
		
	} // end insertAction

	public function deleteAction(){
		
		// echo $id;
		if(isset($_GET['id']) && $_GET['id'] !== ''){
			$id = $_GET['id'];
			$ArrayDeleteId = array($id);
		} else if(isset($_POST['btnDeleteSelect'])){
			// echo 'button click';
			// print_r($_POST['ArrayId']);
			if(isset($_POST['ArrayId']) && ! empty($_POST['ArrayId'])){
				$ArrayDeleteId = $_POST['ArrayId'];
			}
			// return false;
		}
		
		$this->model->deleteSinhvien($ArrayDeleteId);
		$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
	} // end deleteAction

	public function updateAction(){
		// $this->model->editSinhvien();
		if(isset($_GET['id']) && $_GET['id'] !== ''){
			$id = $_GET['id'];
			// echo $id;
			$detailSinhvien = $this->model->detailSinhvien($id);
			// echo "<pre>";
			// print_r($detailSinhvien);
			// $data['detailSinhvien'] = $detailSinhvien;
		}

		if(isset($_POST['btnok'])){
			$params = $_REQUEST;
			// echo $params;
			if($this->checkInputData($params)){
				// echo "valid";
				$SinhvienUpdate = array(
					'sv_name' => $params['txtname'],
					'sv_email' => $params['txtemail'],
					'sv_info' => $params['txtinfo'],
					'sv_address' => $params['txtaddress'],
					'sv_phone' => $params['txtphone'],
					'sv_school' => $params['txtschool'],
					'sv_avata' => $params['txtavata'],
					'sv_gender' => $params['gender']
					);
				$numEmail = $this->model->checkEmail($params['txtemail'], $id);
				// echo $numEmail;
				// return;
				if($numEmail > 0){
					$this->_error['errorEmail'] = "Email đã tồn tại"; 
					// return;
				} else{
					$this->model->editSinhvien($SinhvienUpdate,$id);
					$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
				}
				// echo "<pre>";
				// print_r($SinhvienUpdate);
				
			} // if valid data
			
		} // if bntok
		$data = array_merge((array)$this->_error);
		$data['detailSinhvien'] = $detailSinhvien;
		// echo "<pre>";
		// print_r($data);
		$this->loadView("updateSinhvien",$data);
	} // end updateAction

	private function checkInputData($params){
		$flag = true;
		if( ! $this->library->isNotEmpty($params['txtname'])){
		    $this->_error['errorName'] = "Vui lòng nhập tên sinh vien";
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['txtemail'])){
		    $this->_error['errorEmail'] = "Vui lòng nhập Email"; 
		    $flag = false;
		}else if( ! $this->library->isEmail($params['txtemail'])){
			$this->_error['errorEmail'] = "Email không đúng định dạng"; 
			$flag = false;
		}

		if( ! $this->library->isNotEmpty($params['txtinfo'])){
		    $this->_error['errorInfo'] = "Vui lòng nhập info"; 
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['txtaddress'])){
		    $this->_error['errorAddress'] = "Vui lòng nhập địa chỉ"; 
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['txtphone'])){
		    $this->_error['errorPhone'] = "Vui lòng nhập số điện thoại"; 
		    $flag = false;
		} else if( ! $this->library->isPhone($params['txtphone'])){
			$this->_error['errorPhone'] = "Số điện thoại không đúng định dạng"; 
		}

		if( ! $this->library->isNotEmpty($params['txtschool'])){
		    $this->_error['errorSchool'] = "Vui lòng nhập tên trường"; 
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['txtavata'])){
		    $this->_error['errorAvata'] = "Vui lòng nhập avata"; 
		    $flag = false;
		}

		if(isset($params['gender'])){
			if( ! $this->library->isNotEmpty($params['gender'])){
			    $this->_error['errorGender'] = "Vui lòng nhập giới tính"; 
			    $flag = false;
			}
		} else{
			$this->_error['errorGender'] = "Vui lòng nhập giới tính"; 
			    $flag = false;
		}

		return $flag;
	}
}
