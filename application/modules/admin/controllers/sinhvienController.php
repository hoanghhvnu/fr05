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
		if( isset($_GET['id']) && ctype_digit($_GET['id']) ){
			$page = $_GET['id'];
			// echo $page;
			// echo getType($page);
			// ctype_digit(text)
		} else{
			$page = 1;
		}
		$Perpage = 5;
		$TotalRows = $this->model->totalRows();
		$NumPage = ceil($TotalRows / $Perpage);
		$start =( $page - 1 ) * $Perpage;
		$url = $this->baseurl("/admin/sinhvienController/indexAction/");
		
		if($NumPage > 0){
			$link = "";
			for($i = 1; $i <= $NumPage; $i ++){
				$link .= "<a ";
				$link .= "href = " . $url . $i;
				$link .= ">";
				$link .= $i . "</a>";
				$link .= " ";
			}
			$data['link'] = $link;
		}
		
		$data['lsSinhvien'] = $this->model->listSinhvien($start, $Perpage);
		// echo "<pre>";
		// print_r($data);
		$this->loadView('listSinhvien',$data);
	} // end indexAction

	public function insertAction(){
		$data = array();
		$validInsert = TRUE;
		require("application/library/upload.php");
		$libUpload = new upload();
		if(isset($_POST['btnok'])){
			$params['txtname']    = isset($_POST['txtname']) ? $_POST['txtname'] : "";
			$params['txtemail']   = isset($_POST['txtemail']) ? $_POST['txtemail'] : "";
			$params['txtinfo']    = isset($_POST['txtinfo']) ? $_POST['txtinfo'] : "";
			$params['txtaddress'] = isset($_POST['txtaddress']) ? $_POST['txtaddress'] : "";
			$params['txtphone']   = isset($_POST['txtphone']) ? $_POST['txtphone'] : "";
			$params['txtschool']  = isset($_POST['txtschool']) ? $_POST['txtschool'] : "";
			$params['gender']     = isset($_POST['gender']) ? $_POST['gender'] : "";
			$FileInfo = isset($_FILES['AvataFile']) ? $_FILES['AvataFile'] : '';
			// echo "<pre>";
			// print_r($FileInfo);

			$validFile = TRUE;
			if(! empty($FileInfo) && $FileInfo['error'] == 0){
				// echo "ok file";
				
				$maxSizeImage = 100;//100KB
				
				if( ! $libUpload->CheckTypeUpload($FileInfo, 'image') ){
					$validFile = FALSE;
					$this->_error['errorAvata'] = 'Vui lòng chọn tệp là ảnh';
				}

				if( ! $libUpload->CheckSizeUpload($FileInfo, $maxSizeImage) ){
					$validFile = FALSE;
					$this->_error['errorAvata'] = 'Vui lòng chọn tệp có dung lượng nhỏ hơn ' . $maxSizeImage . "kB";
				}
				// if($validFile == TRUE){
				// 	$saveDir = 'uploads'; // this folder in APPPATH
				// 	$libUpload->doUpload($FileInfo, $saveDir);
				// } // end if validFile
			} else{
				// echo 'error File';
				$this->_error['errorAvata'] = 'Vui lòng chọn ảnh đại diẹn';
				$validFile = FALSE;
			}
			
			if($this->checkInputData($params)){
				
				// $data['detailSinhvien'] = $SinhvienInsert;
				if( ! $this->library->validEmail($params['txtemail'])){
					$this->_error['errorEmail'] = "Email đã tồn tại";
					$validInsert = FALSE;
				}

				if( ! $this->library->validName($params['txtname'])){
					$this->_error['errorName'] = "Name đã tồn tại";
					$validInsert = FALSE;
				}
			} else{
				$validInsert = FALSE;
				// $SinhvienInsert = array();
			} // end if checkInputData

			if($validInsert == TRUE){
				$saveDir = 'uploads'; // this folder in APPPATH
				$imageName = $libUpload->doUpload($FileInfo, $saveDir);
				$SinhvienInsert = array(
					'sv_name'    => $params['txtname'],
					'sv_email'   => $params['txtemail'],
					'sv_info'    => $params['txtinfo'],
					'sv_address' => $params['txtaddress'],
					'sv_phone'   => $params['txtphone'],
					'sv_school'  => $params['txtschool'],
					'sv_avata'   => $imageName,
					'sv_gender'  => $params['gender']
					);
				$this->model->insertSinhvien($SinhvienInsert);
				$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
			}
		} // end if submit
		if(isset($data) && ! empty($data)){
			$oldData = $data;
			$data = array_merge($this->_error,$oldData);
		} else{
			$data = $this->_error;
			// print_r($data);
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
			$data['detailSinhvien'] = $detailSinhvien;
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
				$isValid = TRUE;
				if( ! $this->model->validEmail($params['txtemail'], $id)){
					$this->_error['errorEmail'] = "Email đã tồn tại";
					$isValid = FALSE;
				}

				if( ! $this->model->validName($params['txtname'], $id)){
					$this->_error['errorName'] = "Name đã tồn tại";
					$isValid = FALSE;
				}
				if($isValid === TRUE){
					$this->model->editSinhvien($SinhvienUpdate,$id);
					$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
				}
			} // if valid data
			
		} // if bntok

		$data = array_merge($data,$this->_error);
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

		// if( ! $this->library->isNotEmpty($params['txtavata'])){
		//     $this->_error['errorAvata'] = "Vui lòng nhập avata"; 
		//     $flag = false;
		// }

		if(isset($params['gender'])){
			if( ! $this->library->isNotEmpty($params['gender'])){
			    $this->_error['errorGender'] = "Vui lòng chọn giới tính"; 
			    $flag = false;
			}
		} else{
			$this->_error['errorGender'] = "Vui lòng nhập giới tính"; 
			    $flag = false;
		}

		return $flag;
	}
}
