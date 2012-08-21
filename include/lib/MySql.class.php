<?php
/**
 * Description of MySql
 *
 * @author dedal.qq
 */
class MySql {
    
    private static $object;

    private $link;
    private $db_prefix;
    private $table_value;
    
    private $table_name = '';
    private $rows_list = array();
    private $join = array();
    private $where = '';
    private $sort = array('row' => '', 'sort' => '');
    private $limit = array('start' => 0, 'num' => 0);
    
    private $sql_query;

    private $debug = "0";
    private $sql_num_query;
	
    private function __construct($host, $login, $password, $db_name, $prefix)
    {
        $this->link = mysql_connect($host, $login, $password);
        mysql_select_db($db_name, $this->link);
		
        $this->db_prefix = $prefix;
        $this->db_name = $db_name;

        return 0;
    }
    
    /**
     * @return MySql
     */
    static public function getInstance($host='', $login='', $password='', $db_name='', $prefix='') {
        if (!(self::$object != NULL && self::$object instanceof self)) {
            self::$object = new self($host, $login, $password, $db_name, $prefix);
        }
        return self::$object;
    }
	
    function bug($bug = true)
    {
        $this->debug = $bug;
    }
    
	function char_set($string)
	{
		mysql_set_charset($string, $this->link);
	}
	
    function db_insert($table_name, $data = array())
    {
        $sql_data_id='';
        $sql_data_value='';
        
        foreach ($data as $data_id => $data_value)
        {
			$data_value = str_replace("\\", "", $data_value);
            $data_value = str_replace("'", "''", $data_value);
            $sql_data_id .= "`".$data_id."`,";
            $sql_data_value .= "'".$data_value."',";
        }

        $sql_data_id = substr($sql_data_id, 0, -1);
        $sql_data_value = substr($sql_data_value, 0, -1);

        $sql = "INSERT INTO `".$this->db_name."`.`".$this->db_prefix.$table_name."` (".$sql_data_id.") VALUES (".$sql_data_value.");";

		if ($this->debug == 1)
		{
			echo "[".htmlspecialchars($sql)."]<br />";
		}
		
        $query = mysql_query($sql, $this->link);

		$this->sql_num_query++;
		
		return mysql_insert_id($this->link);
    }
	
	function db_get_id()
	{
		return mysql_insert_id($this->link);
	}

    function db_select_table($table_name)
    {
        $this->table_name = $this->db_prefix.$table_name;
    }

    function db_select_where($where)
    {
        $this->where = $where;
    }
    
    function db_select_limit($start, $num)
    {
        $this->limit['start'] = $start;
        $this->limit['num'] = $num;
    }
    /**
     *
     * @param pages $page_hendler 
     */
    function db_select_page($page_hendler)
    {
        $this->limit['num'] = $page_hendler->get_on_page();
        $this->limit['start'] = (($page_hendler->get_page())-1) * $page_hendler->get_on_page();
    }
    
    function db_select_sort($row, $sort = false)
    {
        $this->sort['row'] = $row;
        $this->sort['sort'] = $sort;
    }
    
    function db_select_join($table_name, $row_name)
    {
        $this->join[] = 'LEFT JOIN `'.$this->db_prefix.$table_name.'` ON `'.$this->db_prefix.$table_name.'`.`id` = `'.$this->table_name.'`.`'.$row_name.'`';
    }
    
    function db_select_rows_list($rows_list = array())
    {
        $this->rows_list = $rows_list;
    }
    
    function db_select_count($row = 'id')
    {
        $tmp_row_list = $this->rows_list;
        $this->rows_list = 'COUNT(`'.$this->table_name.'`.`'.$row.'`) count';
        $result = $this->db_exec(false);
        $this->rows_list = $tmp_row_list;
        return $result['count'][0];
    }
    
    function db_exec($reset = true)
    {
        $this->sql_query = 'SELECT';
        
        if (count($this->rows_list) > 0)
        {
            if (is_array($this->rows_list))
            {
                $this->sql_query.= ' `'.implode('`, `', $this->rows_list).'`';
            }
            else
            {
                $this->sql_query.= ' '.$this->rows_list;
            }
            
        }
        else
        {
            $this->sql_query.= ' *';
        }
        
        if ($this->table_name != '')
        {
            $this->sql_query.= ' FROM `'.$this->table_name.'`';
        }
        else
        {
            return false;
        }
        
        if (count($this->join) > 0)
        {
            $this->sql_query.= ' '.implode(' ', $this->join);
        }
        
        if ($this->where != '')
        {
            $this->sql_query.= ' WHERE '.$this->where;
        }
        
        if ($this->sort['row'] != '')
        {
             $this->sql_query.= ' ORDER BY `'.$this->table_name.'`.`'.$this->sort['row'].'` '.($this->sort['sort'] ? 'ASC' : 'DESC');
        }
        
        if ($this->limit['start'] != 0 || $this->limit['num'] != 0)
        {
            $this->sql_query.= ' LIMIT '.$this->limit['start'].', '.$this->limit['num'];
        }
        
        if ($this->debug == 1)
		{
			echo "[".$this->sql_query."]<br />";
		}
		
        $result = mysql_query($this->sql_query, $this->link);
        
        if ($reset)
        {
            $this->db_select_reset();
        }
        
        return $this->db_resulte($result);
    }
    
    public function db_select_reset()
    {
        $this->table_name = '';
        $this->rows_list = array();
        $this->join = array();
        $this->where = '';
        $this->sort = array('row' => '', 'sort' => '');
        $this->limit = array('start' => 0, 'num' => 0);
    }

    private function db_resulte($result)
    {
        $return_data = array();
        
        for ($i=0; $i<mysql_num_rows($result); $i++)
        {
            for ($j=0; $j<mysql_num_fields($result); $j++)
            {
                $return_data[$i][mysql_field_name($result, $j)] = mysql_result($result, $i, mysql_field_name($result, $j));
            }
        }

        $this->sql_num_query++;

        return $return_data;
    }

    function db_update($table_name, $data, $set_data = array())
    {
        $sql = "UPDATE `".$this->db_name."`.`".$this->db_prefix.$table_name."` SET";
		
        foreach ($set_data as $name => $value)
        {	
			$value = str_replace("'", "''", $value);
			if ($value == "NOW( )")
			{
				$sql .= " `".$name."` = ".$value.",";
			}
			elseif ($value == "")
			{
				$sql .= " `".$name."` = NULL,";
			}
			else
			{
				$sql .= " `".$name."` = '".$value."',";
			}
        }

        $sql = substr($sql, 0, -1);
        $sql .= " WHERE ".$data;
		
		if ($this->debug == 1)
		{
			echo "[".$sql."]<br />";
		}
		
        mysql_query($sql, $this->link);
		
		$this->sql_num_query++;
		
        return 0;
    }


	
	function db_delete($table_name, $data = '')
	{
		$sql = "DELETE FROM `".$this->db_prefix.$table_name."` WHERE ".$data;
		
		if ($this->debug == 1)
		{
			echo "[".$sql."]<br />";
		}
		
		$query = mysql_query($sql, $this->link);

		$this->sql_num_query++;
	}
	
	function set_value($name, $value)
	{
		$sql = "SELECT * FROM `".$this->db_prefix.$this->table_value."` WHERE `name` LIKE '".$name."'";

        $query = mysql_query($sql, $this->link);
		if (mysql_num_rows($query) == 1)
		{
			$sql = "UPDATE `".$this->db_prefix.$this->table_value."` SET `value` = '".$value."' WHERE `name` = '".$name."'";
		}
		else
		{
			$sql = "INSERT INTO `".$this->db_prefix.$this->table_value."` (`name`, `value`) VALUES ('".$name."', '".$value."');";

		}

		mysql_query($sql, $this->link);
		
		$this->sql_num_query++;
	}
	
	function get_value($name)
	{
		$sql = "SELECT * FROM `".$this->db_prefix.$this->table_value."` WHERE `name` LIKE '".$name."'";

        $query = mysql_query($sql, $this->link);
		
		if (mysql_num_rows($query) == 1)
		{
			return mysql_result($query, 0, 'value');
		}
		else
		{
			return null;
		}
		
		$this->sql_num_query++;
	}
	
	function db_inc($table_name, $data, $set_data = array())
	{
		$sql = "UPDATE `".$this->db_name."`.`".$this->db_prefix.$table_name."` SET";

        foreach ($set_data as $name => $value)
        {
            $sql .= " `".$name."` = `".$name."`".$value.",";
        }

        $sql = substr($sql, 0, -1);
        $sql .= " WHERE ".$data;
        mysql_query($sql, $this->link);
		
		$this->sql_num_query++;
		
        return 0;
	}
        
    function sql_num()
    {
        return $this->sql_num_query;
    }
}

?>
