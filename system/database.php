<?php
class database
{
    protected $_connect;
    protected $_result;
    public function connect()
    {
        $this->_connect = mysql_connect(config::hostname,config::username,config::password)
                          or die("Server disconnect".__METHOD__);
        mysql_select_db(config::dbname);
    }
    public function query($sql)
    {
        $this->_result = mysql_query($sql);
    }
    /**
     * return total record
     */
     public function numRows()
     {
        if(!$this->_result){
            die("Not query ".__METHOD__);
        }
        return mysql_num_rows($this->_result);
        
     }
     /**
      * get once record
      */
      public function fetch()
      {
        if(!$this->_result){
            die("Not query ".__METHOD__);
        }
        $data = array();
        $data = mysql_fetch_assoc($this->_result);
        return $data;
      }
      /**
       * 
       * Get all record
       */
      public function fetchAll()
      {
        if(!$this->_result){
            die("Not query ".__METHOD__);
        }
        $data = array();
        while($row = mysql_fetch_assoc($this->_result)){
            $data[] = $row;   
        }
        return $data;
      } 
}