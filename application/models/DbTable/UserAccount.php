<?php

class Application_Model_DbTable_UserAccount extends Zend_Db_Table_Abstract
{

    protected $_name = 'user_account';

    public function loginAdmin($username,$password){
    	$md5_pass=md5($password);
    	
    	//$default_fetch_mode = $this->_db->getFetchMode();
        //$this->_db->setFetchMode(5);
        
    	$sql="SELECT * FROM $this->_name WHERE username='$username' AND password='$md5_pass'";
    	$result=$this->_db->fetchRow($sql);
    	
    	//$this->_db->setFetchMode($default_fetch_mode);
    	return $result;
    	
    }

}

