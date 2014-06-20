<?php
namespace drive
final class mysql {

	private $link;

	private $sql = '';


	public  function __construct($hostname,$username,$password,$database) {
		if (!$this->link = mysql_connect($hostname, $username, $password)) {
      		trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
    	}

    	if (!mysql_select_db($database, $this->link)) {
      		trigger_error('Error: Could not connect to database ' . $database);
    	}

		mysql_query("SET NAMES 'utf8'", $this->link);
		mysql_query("SET CHARACTER SET utf8", $this->link);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $this->link);
		mysql_query("SET SQL_MODE = ''", $this->link);
  	}

	public function get_one($key) {
		$query = $this->query($this->sql);
		if(empty($key)) {
			return $query->row[$key];
		}
		return $query->row;
	}
	public function get_all() {

		$query = $this->query($this->sql);

		return $query->rows;
	}
  	public function query($sql) {
		$resource = mysql_query($sql, $this->link);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = mysql_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				mysql_free_result($resource);

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
			trigger_error('Error: ' . mysql_error($this->link) . '<br />Error No: ' . mysql_errno($this->link) . '<br />' . $sql);
			exit();
    	}
  	}

	public function escape($value) {
		return mysql_real_escape_string($value, $this->link);
	}

  	public function countAffected() {
    	return mysql_affected_rows($this->link);
  	}

  	public function getLastId() {
    	return mysql_insert_id($this->link);
  	}

	public function __destruct() {
		//mysql_close($this->link);
		$this->link = null;
	}
}
?>
