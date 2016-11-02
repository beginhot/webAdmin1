<?php

class Application_Model_DbTable_ImageUpload extends Zend_Db_Table_Abstract
{

    protected $_name = 'image_upload';

    public $img_id;
    public $img_name;
    public $img_url;
    public $updated_timestamp;
    
    
    public function _getData(){
    
    	$default_fetch_mode = $this->_db->getFetchMode();
        $this->_db->setFetchMode(5);
    	$sql="SELECT * FROM ".$this->_name." ORDER BY updated_timestamp DESC ";
    	
		$result=$this->_db->fetchAll($sql);
		$this->_db->setFetchMode($default_fetch_mode);
		return $result;
    }
	
    
	public function insert(){
			
		$sql=" INSERT INTO ".$this->_name."
			 (		
			 	img_name,
			 	img_url,
			 	updated_timestamp
			 ) 
			 VALUES
			 (
			 	'".$this->img_name."',
			 	'".$this->img_url."',
			 	'".$this->updated_timestamp."'
			 )
			 ";

		//return $sql;
		
		try {								
		 	$result=$this->_db->query($sql);    
			if($result){
	        	 $this->_data['msg']="Insert Data Success";
			 	 $this->_data['status']="success"; 
	        }else{
	        	 throw new Exception('Error');      
			}
		}catch (Exception $e){
			 $this->_data['msg']=$e->getMessage();
			 $this->_data['status']="error";   
		}
                
		return $this->_data;
	}
}

