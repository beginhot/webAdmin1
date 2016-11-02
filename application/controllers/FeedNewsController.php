<?php

class FeedNewsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $this->_helper->layout()->disableLayout();
        //============ Get Class Model Load News ============
        $_dbNewsPost=new Application_Model_DbTable_NewsPost();  
        //============= Request Action GetData In Class News Post==========
        $_dataNews=$_dbNewsPost->_getData(); 
        //=========== Send data To View Page =========       
        $this->view->NewsFeed=$_dataNews;
    }

    public function panelFanpageAction()
    {
        // action body
    }

    public function contentNewsAction()
    {
        // action body
        $this->_helper->layout()->disableLayout();
        //============ Get Class Model Load News ============
        $_dbNewsPost=new Application_Model_DbTable_NewsPost();  
        
        //============= Update Read News ======================
       $_dbNewsPost->UpdateReadNews($this->_getParam('news_id','')) ;
        
        //============= Request Action GetData In Class News Post==========        
        $_dataNews=$_dbNewsPost->findByID($this->_getParam('news_id','')); 
        //=========== Send data To View Page =========       
        $this->view->NewsContent=$_dataNews;
    }


}





