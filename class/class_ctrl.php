<?php
class class_ctrl
{	
	/**
	 * @var Array
	 */
	public $response;
	
	public $res_encrypt;
	
	/**
	 * @var Array
	 */
	public $request;
	
	/**
	 * model对象
	 * @var Object 
	 */
	public $model;
	
	
	function __construct($request,$model)
	{
		$this->request	=	$request;
		$this->model	=	$model;
		$this->response['errcode']	=	0;
		$this->response['responseId']	=	$this->request['requestId'] + 1000;
		$this->user_hash	=	trim($this->request['hash']);
	}

	
	protected function common(){
		$this->userId	=	intval($this->request['userId']);
		if(!$this->userId){
			class_util::set_error_response(11);
			throw new Exception ('user id is not find!!' );
		}
	}
	
	protected function check_hash(){

        if(!conf_common::$is_hash)
            return true;

		$hash_str	=	'';
		ksort($this->request);
		foreach ($this->request as $key => $val){
			if($key == 'hash'){
				continue;
			}
			$hash_str.=$val;
		}
		$hash =	 md5($hash_str.conf_common::$secret_key.$this->userkey);
		class_util::log_debug($hash);
		class_util::log_debug($this->user_hash);
		if($hash != $this->user_hash){
			return false;
		}
		return true;
	}
	
	/**
	 * 返回客户端response
	 * @return string
	 */
	public function encrypt_response() {
		$res	=	json_encode($this->response,JSON_UNESCAPED_UNICODE);
		class_util::log_debug( "response json: ".$res);
		$this->res_encrypt	=	class_util::encrypt($res);
	}
	
	public function show_response(){
        if(!conf_common::$is_encrypt)
            return json_encode($this->response,JSON_UNESCAPED_UNICODE);
		if(!$this->res_encrypt){
			$this->encrypt_response();
		}
		return $this->res_encrypt;
	}
}