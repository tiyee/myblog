<?php
namespace drive
final class mssql {

	private $link;

	private $sql = '';

	static private  $_instance;



  	//-------------------------------------------------------------------------------
  	private  function __construct($hostname,$username,$password,$database) {
		if (!$this->link = mssql_connect($hostname,$username,$password)) {
      		trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
    	}
    	/*$conn=mssql_connect('172.16.12.111','sa','dbrootpass');
		mssql_select_db('eap',$conn);*/

    	if (!mssql_select_db($database, $this->link)) {
      		trigger_error('Error: Could not connect to database ' . $database);
    	}


  	}
	private function __clone(){
		//
	}
	public static function getInstance($hostname,$username,$password,$database) {
		if(! (self::$_instance instanceof self) ) {
			self::$_instance = new self($hostname,$username,$password,$database);
		}
		return self::$_instance;
	}
  	//-------------------------------------------------------------------------------

     public function lastInsertId() {

    		$sql = 'SELECT SCOPE_IDENTITY() as id';
    		$query = $this->query($sql);
    		return $query->row['id'];

    }
  	public function query($sql) {
        $resource =  mssql_query($sql);
        //var_dump($resource);
		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = mssql_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}
				//var_dump($data);

				mssql_free_result($resource);

				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;
    		} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . mssql_get_last_message() . '<br />Error No: ' . mssql_get_last_message() . '<br />' . $sql);
			exit();
    	}
  	}
  	public function getLimit($strQuery, $currentPage, $pageSize = 20, $order='order by id ASC') {
        $offset = ($currentPage - 1) * $pageSize;
        $order = trim($order);
        if($order) {
            $strQuery = str_ireplace($order, ' ', $strQuery);
        }

        if(false == $order) {
                DIE('MSSQL server 排序需传入$ORDER参数');
        }
        $strQuery = 'SELECT * FROM (
                SELECT ROW_NUMBER() OVER(' . $order . ') _rownum,* FROM (
                    ' . $strQuery . '
                ) tbl
        ) tbl WHERE _rownum >' . $offset . ' AND _rownum <=' . ($offset + $pageSize);
        //echo $strQuery;
        $query = $this->query($strQuery);
        return $query->rows;

    }
    public function getOne($sql,$currentPage = 1, $pageSize = 1, $order='order by id ASC') {
    	return $this->getLimit($sql, $currentPage, $pageSize, $order);
    }


	public function __destruct() {
		mssql_close($this->link);
	}
}
?>
