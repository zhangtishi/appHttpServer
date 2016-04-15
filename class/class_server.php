<?php
class class_server {
	
	/**
	 * 请求id
	 * @var int
	 */
	private $request_id;
	
	/**
	 * 请求参数数组
	 * @var Array
	 */
	private $request;
	
	
	function __construct($request) {
		$this->request	=	$request;
	}
	
	/**
	 * $_REQUET参数检查
	 * @param array $param        	
	 */
	private function param_check($param) {
		if(!isset($param['requestId'])){
			class_util::set_error_response(103);
			throw new Exception ('requestId is not set!!' );
		}
		$this->request_id	=	$param['requestId'];
		if(!isset(conf_request::$request_param[$this->request_id])){
			class_util::set_error_response(103);
			throw new Exception ('requestId is not found!!' );
		}
		foreach (conf_request::$request_param[$this->request_id] as $val){
			if(!isset($param[$val])){
				class_util::set_error_response(103);
				throw new Exception ("request param {$val} is not found!" );
				break;
			}
		}
		return true;
	}
	
	/**
	 * 执行分发
	 */
	public function route() {
		
		try {
			
			$this->param_check ($this->request);
			
			if (!isset ( conf_request::$request [$this->request_id])) {
				class_util::set_error_response(104);
				throw new Exception ('request id is not find!' );
			}
			
			$model_name = 'model_' . conf_request::$request [$this->request_id];
			$model 	=	new $model_name();
			$model->pdo->mysql_begin();
			
			$ctrl_name = 'ctrl_' . conf_request::$request [$this->request_id];
			$ctrl = new $ctrl_name ($this->request,$model);
			$execute	=	$ctrl->execute ();
			if (!$execute){
				class_util::set_error_response(106);
				throw new Exception ('execute is fail!' );
			}
			$model->pdo->mysql_commit();
			
			return $ctrl->show_response();
			
		}catch (PDOException $Pe) {
			//mysql执行类错误
			if(!is_null($model->pdo->link)){
				$model->pdo->mysql_rollBack ();
				$model->pdo->tran = false;
			}
			class_util::log_error($Pe->getFile(),$Pe->getLine(),$Pe->getMessage());
			class_util::set_error_response(101);
			
			return class_util::show_error_response();
			
		} catch ( Exception $e ) {
			//常规错误
			class_util::log_error($e->getFile(),$e->getLine(),$e->getMessage());
			return class_util::show_error_response();
		}
	}
}