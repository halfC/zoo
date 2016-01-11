<?php
class BaseSql {
	
	public  $mysqli ;
	public 	   $res 	= NULL;
	public 	   $msg 	= array();
	private  $sql;
	/**
	 * @param string $host
	 * @param string $user
	 * @param string $pass
	 * @param string $dbname
	 * @param string $charest
	 */
	public function __construct($config){
		
		$host = $config['host_name'];
		$user = $config['user'];
		$pass = $config['password'];
		$dbname = $config['dbname'];
		$charest='utf8';
		$this->mysqli = new mysqli($host,$user, $pass, $dbname);
		if($this->mysqli->connect_errno){
			$this->mysqli = NULL;
			die('连接错误 :'.$this->mysqli->connect_error);
		}else{
			$this->mysqli->set_charset($charest);
		}
	}
	public function __destruct()
	{
		$this->close();
	}
	/**
	 * 执行sql
	 * @param string $sql
	 * @param string $limit
	 * @return object
	 */
	public function query( $sql, $limit = null )
	{
		$sql = str_replace("\\", "\\\\", $sql);
		$this->res = $this->mysqli->query($sql);
		$this->sql = $sql;
		return $this->res;
	}
	/**
	 * 返回插入的ID
	 * return int
	 */
	public function insertId()
	{
		return $this->mysqli->insert_id;
	}
	/**
	*	get_last_sql()
	*/
	/**
	 * 从结果集取的一行数据作为数组.默认返回数字数组和关联数组
	 * @param unknown_type $sql
	 * @return array 
	 */
	public function fetchArray($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			//MYSQL_ASSOC 得到关联数组 
			//MYSQL_NUM 得到数字索引
			//默认 MYSQL_BOTH 都得到
			$row = $res->fetch_array();
			if($row !== false){
				//第一个字段的值
				return $row[0];
			}else{
				return '';
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 返回数字数组
	 * @param string $sql
	 * @return string|boolean
	 */
	public function fetchRow($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			$row = $res->fetch_row();
			if($row !== false){
				//第一个字段的值
				//return $row;
				return $row[0];
			}else{
				return '';
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 返回关联数组
	 * @param string $sql
	 * @return string|boolean
	 */
	public function fetchAssoc($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			$row = $res->fetch_assoc();
			if($row !== false){
				//第一个字段的值
				return $row;
			}else{
				return '';
			}
		}else{
			return false;
		}
	}
	/**
	 * 新增数据
	 * @param string $table
	 * @param string $data
	 * @return mixed
	 */
	public function insert($table, $data)
	{
		if(!is_array($data)){
			return false;
		}
		$cols = array();
		$val  = array();
		foreach($data as $col => $value){
			$cols[] = $col;
			$vals[]  = "'".trim(addslashes($value)) . "'";
		}
		
		$sql = "INSERT INTO ".$this->escape($table)
				.' (' . implode(',', $cols).')'
				.' VALUES ('.implode(',', $vals).')';
		
		$this->query($sql);
		return $this->affectedRows();
	}
	/**
	 * 更新数据
	 * @param mixed   $table The table to update.
	 * @param array $data
	 * @return int   The number of affected rows.
	 */
	public function update($table, $data, $where='')
	{
		// echo 3433;die;
		if(!is_array($data)){

			return false;
		}
		$set = array();
		foreach($data as $col => $val){
			if(is_array($val)){
				switch ($val[0]){
					case 'inc':$set[] = $col."= $col+$val[1] ";break;
				}
			}else{
				$set[] = $col."='".trim($val)."'";
			}
			
		}
		$where = $this->setWhere($where);
		
		$sql  = "UPDATE ".$this->escape($table)
				.' SET '.implode(',', $set)
				.' WHERE '.$where;
		$this->query($sql);
		return $this->affectedRows();
	}
	/**
	 * 删除数据
	 * @param array $where
	 * @return string
	 */
	public function delete($table, $where='')
	{
		$where = $this->setWhere($where);
		$sql = "DELETE FROM ".$this->escape($table)
			   .' WHERE '.$where;
		$this->query($sql);
		return $this->affectedRows();
	}

	/**
	 * 查询方法-查询所有数据
	 * @param $table
	 * @param $where
	 * @param $order
	 * @param $limit
	 */
	public function findAll($table,$where,$order='',$limit=''){
		$where = $this->setWhere($where);
		$order = empty($order)?'':" ORDER BY ".$order;
		$limit = empty($limit)?'':" LIMIT ".$limit;
		$sql = "SELECT * FROM ".$table." WHERE ".$where.$order.$limit;
		return $this->getAll($sql);
	}

	/**
	 * 查询方法-查询单条数据
	 * @param $table
	 * @param $where
	 * @param $order
	 * @param $limit
	 */
	public function findOne($table,$where,$order='',$limit=''){
		$where = $this->setWhere($where);
		$order = empty($order)?'':" ORDER BY ".$order;
		$limit = empty($limit)?'':" LIMIT ".$limit;
		$sql = "SELECT * FROM ".$table." WHERE ".$where.$order.$limit;
		return $this->getRow($sql);
	}
	/**
	 * 设置where条件
	 * @param array $where
	 * @return string
	 */
	protected function setWhere($where)
	{
		if(empty($where)){
			return $where;
		}
		if(!is_array($where)){
			$where = array($where);
		}
		$set = array();
		foreach ($where as $key=>$val){
			if(is_array($val)){
				switch ($val[0]){
					case 'gt':$set[] = $key.">'".trim($val[1])."'";break;
					case 'lt':$set[] = $key."<'".trim($val[1])."'";break;
					case 'gte':$set[] = $key.">='".trim($val[1])."'";break;
					case 'lte':$set[] = $key."<='".trim($val[1])."'";break;
					case 'like':$set[] = $key." like '".trim($val[1])."'";break;
				}
			}else{
				$set[] = $key."='".trim($val)."'";
			}
		}
		$where = implode(" AND ", $set);
		return $where;
	}
	/**
	 * 取的某个字段的值
	 * @param unknown_type $sql
	 */
	public function getOne($sql)
	{
		$sql = $sql." LIMIT 1";
		$res = $this->query($sql);
		if($res !== false){
			$row = $res->fetch_row();
			if($row !== false){
				return $row['0'];
			}else{
				return '';
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 获取一行关联数组
	 * @param string $sql
	 * @return array
	 */
	public function getRow($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			$row = $res->fetch_assoc();
			if($row !== false){
				return $row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * 获取满足条件的所有记录
	 * @param unknown_type $sql
	 * @return multitype:unknown |boolean
	 */
	public function getAll($sql)
	{
		$arr = array();
		$res = $this->query($sql);
		$this->sql = $sql;
		if($res !== false){
			while($row = $res->fetch_assoc()){
				$arr[] = $row;
			}
			return $arr;
		}else{
			return false;
		}
	}
	
	/**
	 * 返回结果集的行数
	 * @param string $sql
	 * @return mixed|boolean
	 */
	public function numRows($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			$nums= $res->num_rows;
			return $nums;
		}else{
			return false;
		}
	}
	/**
	 * 返回结果集的列数
	 * @param string $sql
	 * @return boolean
	 */
	public function fieldsCount($sql)
	{
		$res = $this->query($sql);
		if($res !== false){
			$nums= $res->field_count;
			return $nums;
		}else{
			return false;
		}
	}
  
	/**
	 * 获取列信息
	 * @param unknown_type $sql
	 * @return multitype:NULL |boolean
	 */
	public function fetchField($sql)
	{
		$res = $this->query($sql);
		$arr = array();
		if($res !== false){
			while ($row = $res->fetch_field()){
				$arr[]['name'] = $row->name;
				$arr[]['table'] = $row->table;
				$arr[]['max_length'] = $row->max_length;
				$arr[]['type'] = $row->type;
			}
			return $arr;
		}else{
			return false;
		}
	}
	
	/**
	 * 从结果集中取得列信息并作为对象返回
	 * @param unknown_type $sql
	 * @return multitype:NULL |boolean
	 */
	public function fetchFields($sql)
	{
		$res = $this->query($sql);
		
		if($res !== false){
			$row = $res->fetch_fields();
			return $row;
		}else{
			return false;
		}
	}
	/**
	 * 在前一个MySQL操作中获取影响的行数
	 */
	public function affectedRows()
	{
		return $this->mysqli->affected_rows;
	}
	
	/**
	 * @return object 
	 */
	public function stmtInit(){
		return $this->mysqli->stmt_init();
	}
	
	/**
	 * @return boolean
	 */
	public function prepare($sql){
		return  $this->mysqli->prepare($sql);
	}
	/* public function bindParam($type,){
		
	} */
	/**
	 * 过滤字符串
	 * @param unknown_type $str
	 * @return string
	 */
	public function escape($str)
	{
		if(is_array($str)){
			foreach ($str as $key=>$val){
				$str[$key] = $this->mysqli->real_escape_string($val);
			}
		}else{
			$str = $this->mysqli->real_escape_string($str);
		}
		
		return $str;
	}
	/**
	 * 释放查询结果集
	 * @param unknown_type $result
	 */
	public function freeResut()
	{
		$this->res->free();
	}
	
	/**
	 * 事务
	 * @param unknown_type $result
	 */
	public function autoCommit($type = true)
	{
		$this->mysqli->autocommit($type);
	}
	
	/**
	 * 提交事务
	 * @param unknown_type $result
	 */
	public function commit()
	{
		$this->mysqli->commit();
	}
	
	/**
	 * 回滚事务
	 * @param unknown_type $result
	 */
	public function rollback()
	{
		$this->mysqli->rollback();
	}
	
	/**
	 * 客户端版本
	 */
	public function clientVersion()
	{
		return $this->mysqli->client_version;
	}
	public function clientInfo()
	{
		return $this->mysqli->client_info;
	}
	public function serverInfo()
	{
		return $this->mysqli->server_info;
	}
	public function serverVersion()
	{
		return $this->mysqli->server_version;
	}
	/**
	 * 返回mysql一个字符串形式的连接类型
	 */
	public function hostInfo()
	{
		return $this->mysqli->host_info;
	}
	/**
	 * 设置错误信息
	 */
	public function setError($code, $error)
	{
		$this->msg['code'] = $code;
		$this->msg['msg']  = $error;
	}
	/**
	 * 获取错误信息
	 * @return multitype:
	 */
	public function getError()
	{
		return $this->msg;
	}
	/**
	 * 返回最近函数调用的错误信息字符串
	 */
	public function error()
	{
		return $this->mysqli->error;
	}
	/**
	 * 返回最近函数调用的错误代码
	 */
	public function errno()
	{
		return $this->mysqli->errno;
	}
	
	/**
	 * TODU 返回调试信息
	 */
	public function debug()
	{
		return $this->mysqli->dump_debug_info();
	}
	/**
	 * 为当前连接返回线程ID
	 */
	public function getThreadId()
	{
		return $this->mysqli->thread_id;
	}
	/**
	 * 获取当前的系统状态。
	 * @return string
	 */
	public function stat()
	{
		return $this->mysqli->stat();
	}
	/**
	 * 返回MySQL协议使用的版本
	 */
	public function protocolVersion()
	{
		return $this->mysqli->protocol_version;
	}
	/**
	 * 返回有关最近执行的查询
	 */
	public function info()
	{
		return $this->mysqli->info;
	}
	/**
	 * 关闭数据库连接
	 */
	public  function close()
	{
		//$this->mysqli->close();
	}
	public function getLastSql(){
		return $this->sql;
	}
}