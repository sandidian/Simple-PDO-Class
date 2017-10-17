<?php  

/**
 * @author : Sandi Dian Lesmana Putra
 * <sandidian@kopihitamcode.com>
 * simple pdo clas
 * http://kopihitamcode.com
 */

	$db = dirname(__FILE__) . '\Db.php';
	require_once $db;

	class Query
	{
		
		public $select;
		public $table;
		public $where;
		public $arrayWhere;
		public $orderBy;
		public $selectFrom;
		public $statement;
		public $join;
		public $data;
		public $limit;
	    public $isModel = false;
	    public $isModelArray = false;

	    public function __construct()
	    {
	       $this->db = Db::instance();
	    }

	    public function find($table)
		{
			
			$this->table = $table;

			$select = $this->selectFrom;
			if(empty($select)){
				$sql = "SELECT * FROM ";
				$this->selectFrom = $sql;
			}

            return $this;
			
		}

		public function select($select)
		{
			$sql = "SELECT {$select} FROM ";
			$this->selectFrom = $sql;
			return $this;
		}
		
		// // public function join($type, $table, $on = '')
	 // //    {
	 // //        $this->data[] = [$type, $table, $on];

	 // //        $sql = "";

	 // //    	for ($i=0; $i <= count($this->data); $i++) { 

	 // //    		if (is_array($this->data[$i]) || is_object($this->data[$i])){

	 // //    			$sql .= "<br>".$this->data[$i][0]." ".$this->data[$i][1]." ON ".$this->data[$i][2];

	 // //    		}
	    		
	 // //    	}

	 // //    	$this->join = $sql;

	 // //    	return $this;
	 // //    }

		public function where($where = [])
		{
	        if(is_array($where)){
				$sql = ' WHERE ';
				$i = 0;
				foreach($where as $key => $value){
					$pre = ($i > 0)?' AND ':'';
					$sql .= "{$pre} {$key} = '{$value}' ";
					$i++;
				}

				$this->where = $sql;
			}else{
				$this->where = " WHERE {$where}";
			}

	        return $this;
	    }

	    public function andFilterWhere($array = [])
	    {
	        if ($array) {

	            $where = $this->where;
	            if (empty($where)) {

	                $where .= " WHERE ";
	                $where .= " " . $array[1] ." " . $array[0] . " '" . $array[2] . "' ";
	            } else {

	                $where .= " AND ";
	                $where .= " " . $array[1] ." " . $array[0] . " '" . $array[2] . "' ";
	            }

	            $this->where = $where;
	        }

	    }

	    public function orFilterWhere($array = [])
	    {

	        return $this;
	    }
	    
	    public function orderBy($orderBy = NULL)
	    {
	        if (!empty($orderBy)) {
	            $this->orderBy = ' ORDER BY ' . $orderBy . ' ';
	        }

	        return $this;
	    }

	    public function innerJoin($join=[]){
	    	if(is_array($join)){
				$sql = '';
				$i = 0;
				foreach($join as $key => $value){
					$sql .= " INNER JOIN {$key} ON {$value}";
				}

				$this->join = $sql;
			}

			return $this;
	    }

	    public function test(){
	    	return $this->selectFrom . $this->table . $this->join . $this->where . $this->orderBy;
	    }

	    public function statement()
	    {
	        $statement = $this->statement;

			if(empty($statement)){
	            return $this->selectFrom . $this->table . $this->join . $this->where . $this->orderBy;
	        }else{
	            return $this->statement;
	        }
	    }

		public function one()
	    {
	    	$statement = $this->statement;

			if(empty($statement)){
				$row = $this->db->prepare($this->statement());
			}else{
				$row = $this->db->prepare($this->statement);
			}

	        $this->limit = " LIMIT 1 ";
	        
	        $row->execute();
	        $this->isModel = true;

	        return $row->fetch(\PDO::FETCH_OBJ);
	    }

	    public function all($type = NULL)
	    {
	        $statement = $this->statement;

			if(empty($statement)){
				$row = $this->db->prepare($this->statement());
			}else{
				$row = $this->db->prepare($this->statement);
			}

	        $row->execute();

	        $this->isModelArray = true;

	        return $row->fetchAll(\PDO::FETCH_OBJ);
	    }

	    public function command($sql)
	    {
	    	$this->statement = $sql;
	    	return $this;
	        
	    }

	    public function delete()
	    {
	    	$this->limit = " LIMIT 1 ";
	        $sql = "DELETE FROM {$this->table} {$this->where} {$this->limit}";
	        $query = $this->db->prepare($sql);

	        return $query->execute();
	        
	    }

	    

	    public function deleteAll()
	    {
	        $sql = "DELETE FROM {$this->table} {$this->where} ";
	        $query = $this->db->prepare($sql);
	        
	        return $query->execute();
	      	
	    }

	    public function save($data)
	    {
	     	$field = "";
	     	foreach ($data as $key => $value) {
	     		$field .= " {$key} = '{$value}' ,";
	     	}
	     	$field = rtrim($field,",");

	     	if ($this->isModel) {
	     		$this->statement = "UPDATE " . $this->table . " SET " . $field . $this->where . $this->limit;
	       	}else{
	       		$this->statement = "INSERT INTO " . $this->table . " SET " . $field;
	       	}

	       	$query = $this->db->prepare($this->statement);
	        
	        return $query->execute();
	
	    }

	}
?>