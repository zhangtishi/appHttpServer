<?php
class class_mysql
{
	/**
	 * 数据库链接句柄
	 */
	public	$link	=	null;
	
	/**
	 * 事务开关
	 */
	public	$tran	=	false;
	
	/**
	 * 最后插入自增id
	 */
	public $last_id;
	
	/**
	 * 构造函数  初始化数据库链接
	 */
	function __construct(){
		if (!extension_loaded('pdo') || !extension_loaded('pdo_mysql')){
			throw new PDOException ('pdo or pdo_mysql is not extension!' );
		}
		$this->link	=	null;
		$this->tran	=	false;
		$this->last_id	=	null;
	}
	
	/**
	 * 链接主数据库
	 */
	private function mysql_conn()
	{
		if(!is_null($this->link))
		{
			return $this->link;
		}
		$connect = 'mysql:dbname=' . conf_mysql::$master['database'] .';host=' . conf_mysql::$master['host'];

		$this->link = new PDO($connect, conf_mysql::$master['user'],conf_mysql::$master['pass'],
				array(
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				));

		return $this->link;
	}
	
	/**
	 * 开启事务
	 */
	public function mysql_begin(){

		if (is_null($this->link)){
			$this->mysql_conn();
		}

		if($this->tran	==	true){
			return ;
		}
		$this->link->beginTransaction();
		$this->tran	=	true;
	}

    /**
     * 开启事务
     */
    public function mysql_rollBack(){

        if (is_null($this->link)){
            $this->mysql_conn();
        }

        if($this->tran	==	false){
            return ;
        }
        
        $this->link->rollBack();
        $this->tran	=	false;
    }
	
	/**
	 * 提交事务
	 */
	public function mysql_commit(){
		
		if($this->tran	==	false){
			return ;
		}
		
		if (is_null($this->link)){
			throw new PDOException ('error msg: mysql link lost!' );
		}		
		
		if(class_util::$error){
			return ;
		}
		$this->link->commit();
		$this->link->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
		$this->tran	=	false;
		return true;
	}
	
	/**
	 * 返回最后插入的自增id
	 */
	public function last_id()
	{
		if (is_null($this->link))
		{
			$this->mysql_conn();
		}
		return $this->link->lastInsertId();
	}
	
	/**
	 * 执行 insert update delete
	 * 
	 * @param sting $sql        	
	 * @param array $bind    绑定参数    	
	 */
	public function mysql_set($sql, $bind = array()) {
		if (is_null ( $this->link )) {
			throw new PDOException ('link is lost' );
		}
		if ($this->tran == false) {
			return;
		}
		if(class_util::$error)
		{
			return ;
		}

		$stmt = $this->link->prepare ( $sql );
			
		if (!empty ( $bind ) && is_array ( $bind )) {
			foreach ( $bind as $k => $v ){
				$stmt->bindValue (":".$k, $v );
			}
		}

        $execute = $stmt->execute ();
		if (!$execute) {
			throw new PDOException ('sql语句' . $sql . '错误' );
		}
		
		if (preg_match ( "/^\s*(INSERT\s+INTO|REPLACE\s+INTO)\s+/i", $sql )) {
			$this->last_id = $this->last_id ();
		} else {
			$this->last_id = null;
		}

        return true;
	}
	
	/**
	 * 执行select  取单条记录
	 * @param sting $sql
	 */
	public function mysql_get_one($sql,$bind = array())
	{
		if (is_null ( $this->link )) {
			throw new PDOException ('link is lost' );
		}
		$stmt 		= $this->link->prepare($sql);		
		if (!empty ( $bind ) && is_array ( $bind )) {
			foreach ( $bind as $k => $v ){
				$stmt->bindValue (":".$k, $v );
			}
		}		
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * 执行select  取多条记录
	 * @param sting $sql
	 * @param array $bind
	 */
	public function mysql_get_all($sql,$bind = array()) {
		if (is_null ( $this->link )) {
			throw new PDOException ('link is lost' );
		}
		$stmt = $this->link->prepare ( $sql );
		if (! empty ( $bind ) && is_array ( $bind )) {
			foreach ( $bind as $k => $v ) {
				$stmt->bindValue (":".$k, $v );
			}
		}
		$execute = $stmt->execute();
		if (!$execute) {
			throw new PDOException ('sql语句' . $sql . '错误' );
		}
		return $stmt->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	/**
	 * 执行select  取一个字段单个值
	 * @param sting $sql
	 */
	public function mysql_get_field($sql,$bind = array()) {
		if (is_null ( $this->link )) {
			throw new PDOException ('link is lost' );
		}
		$stmt 		= $this->link->prepare($sql);
		if (!empty ( $bind ) && is_array ( $bind )) {
			foreach ( $bind as $k => $v ){
				$stmt->bindValue (":".$k, $v );
			}
		}
		$execute = $stmt->execute();
		if (!$execute) {
			throw new PDOException ('sql语句' . $sql . '错误' );
		}
		$result	= $stmt->fetch(PDO::FETCH_NUM);
		if($result)
		{
			return $result[0];
		}
		return null;
	}
	
	/**
	 * 执行select  取某一列 返回一维数组
	 * @param sting $sql
	 */
	public function mysql_get_line($sql,$bind = array()) {
		if (is_null ( $this->link )) {
			throw new PDOException ('link is lost' );
		}
		$stmt 		= $this->link->prepare($sql);
		if (!empty ( $bind ) && is_array ( $bind )) {
			foreach ( $bind as $k => $v ){
				$stmt->bindValue (":".$k, $v );
			}
		}
		$execute = $stmt->execute();
		if (!$execute) {
			throw new PDOException ('sql语句' . $sql . '错误' );
		}
		$result	= $stmt->fetchAll(PDO::FETCH_NUM);
		if($result){
			$res = array();
			foreach ($result as $key => $value){
				$res[$key]	=	$value[0];
			}
			return $res;
		}
		return null;
	}
}