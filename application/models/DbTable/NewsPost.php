<?php

class Application_Model_DbTable_NewsPost extends Zend_Db_Table_Abstract
{

    protected $_name = 'news_post';

    public $news_id;
	public $news_title;
	public $news_short;
	public $news_content;
	public $news_picture;
	public $updated_timestamp;
	public $active='Y';
    public $_data=array();
    
    public function _getData(){
    	$default_fetch_mode = $this->_db->getFetchMode();
        $this->_db->setFetchMode(5);
    	$sql="SELECT * FROM ".$this->_name." WHERE active='$this->active' ORDER BY updated_timestamp DESC ";
    	
		$result=$this->_db->fetchAll($sql);
		$this->_db->setFetchMode($default_fetch_mode);
		return $result;
    }
    
    
    public function searchData($key_word){
    		
    	$default_fetch_mode = $this->_db->getFetchMode();
        $this->_db->setFetchMode(5);
    	$sql="SELECT * FROM ".$this->_name." 
    		WHERE active='$this->active' AND (news_title LIKE '%$key_word%' OR news_content LIKE '%$key_word%') 
    		ORDER BY updated_timestamp DESC ";
    	
		$result=$this->_db->fetchAll($sql);
		$this->_db->setFetchMode($default_fetch_mode);
		return $result;
    }
    
    public function findByID($news_id){
    
    	$default_fetch_mode = $this->_db->getFetchMode();
        $this->_db->setFetchMode(5);
    	$sql="SELECT * FROM $this->_name WHERE news_id=$news_id";
    	$result=$this->_db->fetchRow($sql);
    	$this->_db->setFetchMode($default_fetch_mode);
    	return $result;
    }
    
	public function insert(){
	
		
		$sql=" INSERT INTO ".$this->_name."
			 (		
			 	news_title,
			 	news_short,
			 	news_content,
			 	news_picture,
			 	updated_timestamp
			 ) 
			 VALUES
			 (
			 	'".$this->news_title."',
			 	'".$this->news_short."',
			 	'".$this->news_content."',
			 	'".$this->news_picture."',
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
	
	public function update($data, $where){
	
		$sql="UPDATE $this->_name SET $data WHERE $where ";
		//return $sql;
		try{
		 	
			$result=$this->_db->query($sql);
			if($result){
				$this->_data['msg']="Update Data Success";
			 	$this->_data['status']="success";
			}else{
				throw new Exception("Error");
			}
			
		}catch (Exception $e){
			$this->_data['msg']=$e->getMessage();
			$this->_data['status']="error";   
		}
        
        return $this->_data;
	}
	
	public function updateActive($news_id,$active){
	
		$sql="UPDATE $this->_name SET active='$active' WHERE news_id=$news_id ";
		try{
		 	
			$result=$this->_db->query($sql);
			if($result){
				$this->_data['msg']="Update Data Success";
			 	$this->_data['status']="success";
			}else{
				throw new Exception("Error");
			}
			
		}catch (Exception $e){
			$this->_data['msg']=$e->getMessage();
			$this->_data['status']="error";   
		}
        
        return $this->_data;
		
		
	}
	
	public function delete($where){
	
		 $sql="DELETE FROM $this->_name WHERE ".$where;
		
		 try{
		 	
			$result=$this->_db->query($sql);
			if($result){
				$this->_data['msg']="Delete Data Success";
			 	$this->_data['status']="success";
			}else{
				throw new Exception("Error");
			}
			
		}catch (Exception $e){
			$this->_data['msg']=$e->getMessage();
			$this->_data['status']="error";   
		}
        
        return $this->_data;
	}
	
	public function countNews(){
	
		$sql_all="SELECT IFNULL(count(news_id),0) AS num_all FROM news_post WHERE active='Y' ";
		$sql_bin="SELECT IFNULL(count(news_id),0) AS num_bin FROM news_post WHERE active='N' ";
		
		$rowAll=$this->_db->fetchRow($sql_all);
		$rowBin=$this->_db->fetchRow($sql_bin);
		$data['num_all']=$rowAll['num_all'];
		$data['num_bin']=$rowBin['num_bin'];		
		
		return $data;
	}
	
	public function UpdateReadNews($news_id){
	
		
		$sql="select IFNULL(read_news,0)+1 AS sumRead FROM news_post WHERE news_id=$news_id";
		$sumRead=$this->_db->fetchRow($sql);
		$readNews= $sumRead['sumRead'];
		
		$sql_read="UPDATE $this->_name SET read_news=$readNews WHERE news_id=$news_id ";
		try{
		 	
			$result=$this->_db->query($sql_read);
			if($result){
				$this->_data['msg']="Update Data Success";
			 	$this->_data['status']="success";
			}else{
				throw new Exception("Error");
			}
			
		}catch (Exception $e){
			$this->_data['msg']=$e->getMessage();
			$this->_data['status']="error";   
		}
        
        return $this->_data;
		
	}
    

}

