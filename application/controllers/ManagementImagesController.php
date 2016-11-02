<?php

class ManagementImagesController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
        // action body
        $_dbImgUpload= new Application_Model_DbTable_ImageUpload();
        
        $result=$_dbImgUpload->_getData();
		$this->view->result=$result;
        //Zend_Debug::dump($result);
        
        
        //============ Get Data In Form =================
    	$fileName= $_FILES['inputFileUploads']['name'];
    	$fileTMP= $_FILES['inputFileUploads']['tmp_name'];
    	$filesize=$_FILES["inputFileUploads"]["size"];
    	
    	$img_name= $this->_getParam('input_name_img','');
		
    	//=============== Mode Add Data Check Img ===================
    	$msg="";
    	if($fileName!=""){    		
    		$x=explode(".", $fileName);
        	$fileext=end($x);
        	
        	 $tmp_filename="img_upload_".date('YmdHis').".".$fileext;
        	 
        	  if(file_exists("images/uploads")){        	  	
        	  	$destination_file="images/uploads/".$tmp_filename;
        	  }else{
        	  	 mkdir("images/uplod_news",0777);
        	  	 $destination_file="images/uploads/".$tmp_filename;        	  			        
        	  }

        	  //============ Upload File ===============
    		  if(move_uploaded_file($fileTMP,$destination_file)){
		         $msg=$tmp_filename;      
		      }  
			@unlink($_FILES['inputFileUploads']);
        	  
	    	if($msg){    		
	    	
		    	$_dbImgUpload->img_name=$img_name;
				$_dbImgUpload->img_url="http://localhost:10085/images/uploads/$msg";
		    	$_dbImgUpload->updated_timestamp=date('Y-m-d H:i:s');	 
		    	   	
		    	$insert_result=$_dbImgUpload->insert();
		    	//$this->view->result_insert=$insert_result;				
				$this->_redirect("/management-images/index");  		    	    		    	
	    	}	    	
	    }else{
    		$this->view->status="warning";
	    }
    }


}

