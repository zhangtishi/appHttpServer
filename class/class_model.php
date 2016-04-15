<?php
class class_model
{
	/**
	 * pdo mysql 数据库对象
	 * @var Object
	 */
	public  $pdo;
	
	/**
	 * 组织sql语句对象
	 * @var Object
	 */
	public	$sql;
	
	function __construct()
	{
		$this->sql		=	new class_sql();
		$this->pdo		=	new class_mysql();
	}

}
