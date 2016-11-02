<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // Start 
        //=========== Check Cookie Login if no login redirect to page view login ==========
        if(!$_COOKIE['user_id']){
        	$this->_redirect("/index/login");
        }else{
         	$dbNewsPost=new Application_Model_DbTable_NewsPost();
         	$result=$dbNewsPost->countNews();
        	$this->view->countRows=$result;
        }        	             
                
       
    }

    public function loginAction()
    {
        // action body
     	if($_COOKIE['user_id']){
        	$this->_redirect("/index/index");
        }
        
    	$username=$this->_getParam('userName');
    	$password=$this->_getParam('userPassword');
    	
		//========== If Login =============
    	if($username&&$password){
    		$dbUserAccount=new Application_Model_DbTable_UserAccount();
    		$result=$dbUserAccount->loginAdmin($username, $password);
    		
	    	if($result){
	    		 setcookie("user_id",$result['user_id'],time()+24*60*60);
	    		 $this->_redirect("/index/index");
	    		//echo "true";
	    	}else{
	    		//echo "false";
	    		$this->view->msg_status="error";
	    	}
	    	
	    	//Zend_Debug::dump($result);
    	}else{
    		$this->view->msg_status="logout";
    	}
    	 
    }

    public function logoutAction()
    {
        // action body
        setcookie('user_id', ""); 
        $this->_redirect("/index/index");
    }

    public function addNewsAction()
    {
    	$this->_helper->layout()->disableLayout();
        // action body
        $title_news=$this->_getParam('title_news','');
        $news_short=$this->_getParam('input_news_short','');
    	$editor_add_news=$this->_getParam('editor_add_news','');
    	
    	//=================== Model ================
    	$_dbNewsPost=new Application_Model_DbTable_NewsPost();    	
    	
    	//============ Check Data To Insert ================
    	if($title_news&&$editor_add_news){
    		//============ addslashes to string content news ========
    		$news_content=addslashes($editor_add_news);
    		
    		
    		
    		 //============ Get Data In Form =================
	    	$fileName= $_FILES['inputImgTitle']['name'];
	    	$fileTMP= $_FILES['inputImgTitle']['tmp_name'];
	    	$filesize=$_FILES["inputImgTitle"]["size"];
    		
	    	//================ Check Image Upload =========
	    	$msg="";
	    	if($fileName!=""){    		
	    		$x=explode(".", $fileName);
	        	$fileext=end($x);
	        	
	        	 $tmp_filename="img_title_".date('YmdHis').".".$fileext;
	        	 
	        	  if(file_exists("images/title")){        	  	
	        	  	$destination_file="images/title/".$tmp_filename;
	        	  }else{
	        	  	 mkdir("images/title",0777);
	        	  	 $destination_file="images/title/".$tmp_filename;        	  			        
	        	  }
	
	        	  //============ Upload File ===============
	    		  if(move_uploaded_file($fileTMP,$destination_file)){
			         $msg=$tmp_filename;      
			      }  
				@unlink($_FILES['inputImgTitle']);
	        	  
		    	if($msg){    		
		    	
		    		$_dbNewsPost->news_title=$title_news;
		    		$_dbNewsPost->news_short=$news_short;
		    		$_dbNewsPost->news_content=$news_content;
		    		$_dbNewsPost->news_picture=$msg;
		    		$_dbNewsPost->updated_timestamp=date('Y-m-d H:i:s');
			    	    		    	
		    		//========== request function insert data ==========
		    		$result=$_dbNewsPost->insert();
		    		//============ redirect to file index ========= 
		    		$this->_redirect("/index/index?page=views");
		    	}	    	
		    }
	    		    	
    		
    		
    	}
    	
    	
    	//============= Check Mode Set Acive News (เก็บลงถังขยะ)=====================
    	
    	
    	
    }

    public function editNewsAction()
    {
        $this->_helper->layout()->disableLayout();
        $news_id=$this->_getParam('news_id','');
        
        $_dbNewsPost=new Application_Model_DbTable_NewsPost();
        
         // ===== Load data Set To Form =============  
         if($news_id){
        	$result=$_dbNewsPost->findByID($news_id);        
        	$this->view->dataNews=$result; 
         }
         
         //========= On Form Submit send data to update ===========
        $news_id_edit=$this->_getParam('input_news_id_edit','');             
		$title_news=$this->_getParam('title_news_edit','');   
		$news_short=$this->_getParam('input_news_short_edit','');    	
    	$news_content=$this->_getParam('editor_news_edit','');
    	
    	$news_picture=$this->_getParam('input_name_imageTitle_edit','');
    	
    	//============ Get Data In Form =================
	    	$fileName= $_FILES['inputImgTitle_edit']['name'];
	    	$fileTMP= $_FILES['inputImgTitle_edit']['tmp_name'];
	    	$filesize=$_FILES["inputImgTitle_edit"]["size"];
    		
	    	//================ Check Image Upload =========
	    	$msg="";
	    	if($fileName!=""){    		
	    		$x=explode(".", $fileName);
	        	$fileext=end($x);
	        	
	        	 $tmp_filename="img_title_".date('YmdHis').".".$fileext;
	        	 
	        	  if(file_exists("images/title")){        	  	
	        	  	$destination_file="images/title/".$tmp_filename;
	        	  }else{
	        	  	 mkdir("images/title",0777);
	        	  	 $destination_file="images/title/".$tmp_filename;        	  			        
	        	  }
	
	        	  //============ Upload File ===============
	    		  if(move_uploaded_file($fileTMP,$destination_file)){
			         $msg=$tmp_filename;      
			         $news_picture= $tmp_filename;	
			      }  
				@unlink($_FILES['inputImgTitle']);
	        		      
		    }
    	
		    
    	
    	if($title_news&&$news_content&&$news_id_edit&&$news_picture){
    		
    		$data=" news_title='$title_news',news_short='$news_short', news_content='$news_content',news_picture='$news_picture', updated_timestamp='".date('Y-m-d H:i:s')."', updated_by_user_id='".$_COOKIE['user_id']."' ";
			$where=" news_id='$news_id_edit' ";	

			//============ Function Update Data =============
    		$result_update=$_dbNewsPost->update($data, $where);    	
    		$this->_redirect("/index/index?page=views");      		
    	}
    	                 
    }

    public function dataNewsAction()
    {
    	$this->_helper->layout()->disableLayout();
        //============ Load Data Show All News ============
        $_dbNewsPost=new Application_Model_DbTable_NewsPost();  
        
       
        
        //============== check case search data ==============
        $word_search=$this->_getParam('input_search_data_news','');
        $mode_serach=$this->_getParam('mode_search','');
       
        if($word_search){
        	$_dataNews=$_dbNewsPost->searchData($word_search);                	
        	$this->view->dataNews=$_dataNews;      
        }else{
         	$_dataNews=$_dbNewsPost->_getData();        
        	$this->view->dataNews=$_dataNews;
        }

        
        //======== Check Case Delete data to bin ===============
        $active_news_id=$this->_getParam('news_id_confirm_delete','');
        if($active_news_id){
        	
        	$result_active=$_dbNewsPost->updateActive($active_news_id, 'N');
        	if($result_active['status']=="success"){
        		$this->_redirect("/index/index?page=views");
        	}else{
        		$this->_redirect("/index/index");
        	}
        
        }
        
    }

    public function dataBinsAction()
    {
    	$this->_helper->layout()->disableLayout();
        // action body
         //============ Load Data News Bin  ============
        $_dbNewsPost=new Application_Model_DbTable_NewsPost(); 
        //========== Set Where active='N' =============== 
        $_dbNewsPost->active='N';
        //$_dataNews=$_dbNewsPost->_getData();        
        //$this->view->dataNewsBin=$_dataNews;
        
    	//============== check case search data ==============
        $word_search=$this->_getParam('keyword','');
       	
        if($word_search){        	
        	$_dataNews=$_dbNewsPost->searchData($word_search);                	
        	$this->view->dataNewsBin=$_dataNews;      
        }else{        	
         	$_dataNews=$_dbNewsPost->_getData();        
        	$this->view->dataNewsBin=$_dataNews;
        }
        
        
        
        //================ Case Recover Data =============
    	$active_news_id=$this->_getParam('active_news_id','');
        if($active_news_id){
        	$result_active=$_dbNewsPost->updateActive($active_news_id, 'Y');
        	if($result_active['status']=="success"){
        		$this->_redirect("/index/index?page=news_bin");
        	}else{
        		$this->_redirect("/index/index");
        	}
        
        }
        
    	//================ Case Delete Data Forever =============
    	$news_id_delete=$this->_getParam('news_id_confirm_delete_forerver','');
        if($news_id_delete){
        	
        	$result_active=$_dbNewsPost->delete(" news_id=$news_id_delete ");
        	if($result_active['status']=="success"){
        		$this->_redirect("/index/index?page=news_bin");
        	}else{
        		$this->_redirect("/index/index");
        	}
        
        }
        
    }

    public function searchNewsAction()
    {
        // action body
    }


}















