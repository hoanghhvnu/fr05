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
		require("application/library/pagination.php");
		$libPagination = new pagination();
		// $this->loadModel("sinhvienModel");
		if( isset($_GET['id']) && ctype_digit($_GET['id']) ){
			$page = $_GET['id'];
			// echo $page;
			// echo getType($page);
			// ctype_digit(text)
		} else{
			$page = 1;
		}
		$Perpage = 2;
		// $TotalRows = $this->model->totalRows();
		// $NumPage = ceil($TotalRows / $Perpage);
		$url = $this->baseurl("/admin/sinhvienController/indexAction");
		$libPagination->setPerpage($Perpage);
		$libPagination->setTotalItem($this->model->totalRows());
		$libPagination->setBaseUrl($url);

		$start =( $page - 1 ) * $Perpage;
		
		
		// if($NumPage > 0){
		// 	$link = "";
		// 	for($i = 1; $i <= $NumPage; $i ++){
		// 		$link .= "<a ";
		// 		$link .= "href = " . $url . $i;
		// 		$link .= ">";
		// 		$link .= $i . "</a>";
		// 		$link .= " ";
		// 	}
		// 	$data['link'] = $link;
		// }

		$data['link'] = $libPagination->createLink();
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
			$params['sv_name']    = isset($_POST['txtname']) ? $_POST['txtname'] : "";
			$params['sv_email']   = isset($_POST['txtemail']) ? $_POST['txtemail'] : "";
			$params['sv_info']    = isset($_POST['txtinfo']) ? $_POST['txtinfo'] : "";
			$params['sv_address'] = isset($_POST['txtaddress']) ? $_POST['txtaddress'] : "";
			$params['sv_phone']   = isset($_POST['txtphone']) ? $_POST['txtphone'] : "";
			$params['sv_school']  = isset($_POST['txtschool']) ? $_POST['txtschool'] : "";
			$params['sv_gender']     = isset($_POST['gender']) ? $_POST['gender'] : "";
			$data['EnteredData'] = $params;

			$FileInfo = isset($_FILES['AvataFile']) ? $_FILES['AvataFile'] : '';

			if(! empty($FileInfo) && $FileInfo['error'] == 0){
				$maxSizeImage = 100;//100KB
				
				if( ! $libUpload->CheckTypeUpload($FileInfo, 'image') ){
					$validInsert = FALSE;
					$this->_error['errorAvata'] = 'Vui lòng chọn tệp là ảnh';
				}

				if( ! $libUpload->CheckSizeUpload($FileInfo, $maxSizeImage) ){
					$validInsert = FALSE;
					$this->_error['errorAvata'] = 'Vui lòng chọn tệp có dung lượng nhỏ hơn ' . $maxSizeImage . "kB";
				}
			} else{
				// echo 'error File';
				$this->_error['errorAvata'] = 'Vui lòng chọn ảnh đại diẹn';
				$validInsert = FALSE;
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
			} // end if checkInputData

			if($validInsert == TRUE){
				$saveDir = 'uploads'; // this folder in APPPATH
				$imageName = $libUpload->doUpload($FileInfo, $saveDir);
				$SinhvienInsert = array(
					'sv_name'    => $params['sv_name'],
					'sv_email'   => $params['sv_email'],
					'sv_info'    => $params['sv_info'],
					'sv_address' => $params['sv_address'],
					'sv_phone'   => $params['sv_phone'],
					'sv_school'  => $params['sv_school'],
					'sv_avata'   => $imageName,
					'sv_gender'  => $params['sv_gender']
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
		$data = array();
		$validInsert = TRUE;
		$NewImage = TRUE;
		require("application/library/upload.php");
		$libUpload = new upload();
		
		if(isset($_GET['id']) && $_GET['id'] !== ''){
			$id = $_GET['id'];
			$OldDetailSinhvien = $this->model->detailSinhvien($id);
			$data['detailSinhvien'] = $OldDetailSinhvien;

			if(isset($_POST['btnok'])){
				$params['sv_name']    = isset($_POST['txtname']) ? $_POST['txtname'] : "";
				$params['sv_email']   = isset($_POST['txtemail']) ? $_POST['txtemail'] : "";
				$params['sv_info']    = isset($_POST['txtinfo']) ? $_POST['txtinfo'] : "";
				$params['sv_address'] = isset($_POST['txtaddress']) ? $_POST['txtaddress'] : "";
				$params['sv_phone']   = isset($_POST['txtphone']) ? $_POST['txtphone'] : "";
				$params['sv_school']  = isset($_POST['txtschool']) ? $_POST['txtschool'] : "";
				$params['sv_gender']     = isset($_POST['gender']) ? $_POST['gender'] : "";
				$FileInfo = isset($_FILES['AvataFile']) ? $_FILES['AvataFile'] : '';
				$data['detailSinhvien'] = $params;
				// echo "<pre>";
				// print_r($FileInfo);

				// $validInsert = TRUE;
				if(! empty($FileInfo) && $FileInfo['error'] == 0){

					$maxSizeImage = 100;//100KB
					
					if( ! $libUpload->CheckTypeUpload($FileInfo, 'image') ){
						$validInsert = FALSE;
						$this->_error['errorAvata'] = 'Vui lòng chọn tệp là ảnh';
					}

					if( ! $libUpload->CheckSizeUpload($FileInfo, $maxSizeImage) ){
						$validInsert = FALSE;
						$this->_error['errorAvata'] = 'Vui lòng chọn tệp có dung lượng nhỏ hơn ' . $maxSizeImage . "kB";
					}
				} else{
					$NewImage = FALSE;
				}
				// $params = $_REQUEST;
				// echo $params;
				if($this->checkInputData($params)){
					if( ! $this->library->validEmail($params['sv_email'], $id)){
						$this->_error['errorEmail'] = "Email đã tồn tại";
						$validInsert = FALSE;
					}

					if( ! $this->library->validName($params['sv_name'], $id)){
						$this->_error['errorName'] = "Name đã tồn tại";
						$validInsert = FALSE;
					}
					if($validInsert === TRUE){
						if($NewImage == FALSE){
							$imageName = $OldDetailSinhvien['sv_avata'];

						} else{
							$saveDir = 'uploads'; // this folder in APPPATH
							$imageName = $libUpload->doUpload($FileInfo, $saveDir);
						}
						// var_dump( $imageName);
						$SinhvienUpdate = array(
							'sv_name'    => $params['sv_name'],
							'sv_email'   => $params['sv_email'],
							'sv_info'    => $params['sv_info'],
							'sv_address' => $params['sv_address'],
							'sv_phone'   => $params['sv_phone'],
							'sv_school'  => $params['sv_school'],
							'sv_avata'   => $imageName,
							'sv_gender'  => $params['sv_gender']
							);
						// echo "<pre>";
						// print_r($SinhvienUpdate);
						$this->model->editSinhvien($SinhvienUpdate,$id);
						$this->redirect($this->baseurl("/admin/sinhvienController/indexAction"));
					}
				} // if valid data
				
			} // if bntok

			if(isset($_POST['btnReset'])){
				$data['detailSinhvien'] = $OldDetailSinhvien;
			} // end if btnREset
		} // end if isset id

		

		$data = array_merge($data,$this->_error);
		$this->loadView("updateSinhvien",$data);
	} // end updateAction

	private function checkInputData($params){
		$flag = true;
		if( ! $this->library->isNotEmpty($params['sv_name'])){
		    $this->_error['errorName'] = "Vui lòng nhập tên sinh vien";
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['sv_email'])){
		    $this->_error['errorEmail'] = "Vui lòng nhập Email"; 
		    $flag = false;
		}else if( ! $this->library->isEmail($params['sv_email'])){
			$this->_error['errorEmail'] = "Email không đúng định dạng"; 
			$flag = false;
		}

		if( ! $this->library->isNotEmpty($params['sv_info'])){
		    $this->_error['errorInfo'] = "Vui lòng nhập info"; 
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['sv_address'])){
		    $this->_error['errorAddress'] = "Vui lòng nhập địa chỉ"; 
		    $flag = false;
		}

		if( ! $this->library->isNotEmpty($params['sv_phone'])){
		    $this->_error['errorPhone'] = "Vui lòng nhập số điện thoại"; 
		    $flag = false;
		} else if( ! $this->library->isPhone($params['sv_phone'])){
			$this->_error['errorPhone'] = "Số điện thoại không đúng định dạng"; 
		}

		if( ! $this->library->isNotEmpty($params['sv_school'])){
		    $this->_error['errorSchool'] = "Vui lòng nhập tên trường"; 
		    $flag = false;
		}

		// if( ! $this->library->isNotEmpty($params['txtavata'])){
		//     $this->_error['errorAvata'] = "Vui lòng nhập avata"; 
		//     $flag = false;
		// }

		if(isset($params['sv_gender'])){
			if( ! $this->library->isNotEmpty($params['sv_gender'])){
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
