<?php
class class_util 
{
	
	public static $error = null;
	
	/**
	 * 返回错误信息
	 */
	public static function show_error_response(){

        if(conf_common::$is_encrypt)
        {
            self::$error = self::encrypt(self::$error);
        }
		return self::$error;
	}
	
	/**
	 * 设置错误信息
	 * @param int $code
	 */
	public static  function set_error_response($code) {
		self::$error	=	json_encode(['errcode'=>$code,
                                         'success'=>0],JSON_UNESCAPED_UNICODE) ;
        //self::$error	=	json_encode(['errcode'=>$code],JSON_UNESCAPED_UNICODE) ;
	}
	
	//===============================================================
	
	
	/**
	 * 字符串转byte数组
	 * @param string $string
	 */
	public static function getBytes($string) {
	
		$bytes = array();
		for($i = 0; $i < strlen($string); $i++){
			$bytes[] = ord($string[$i]);
		}
		return $bytes;
	}
	
	/**
	 * byte数组转字符串
	 * @param array $bytes
	 */
	public static function toStr($bytes) {
	
		$str = '';
		foreach($bytes as $ch) {
			$str .= chr($ch);
		}
	
		return $str;
	}
	
	/**
	 * 加密函数
	 * @param string $txt	//要加密的字符串
	 * @param string $key	//秘钥
	 */
	public static function encrypt($txt){
	
		$key = self::getBytes(conf_common::$secret_key);
		$txt = self::getBytes($txt);
		$ctr=0;
		$tmp = array();
		foreach($txt as $k => $val){
			if ($ctr==count($key)) {
				$ctr=0;
			}
			$tmp[$k] = $val ^ $key[$ctr];
			$ctr++;
		}
		return base64_encode(self::toStr($tmp));
	}
	
	/**
	 * 解密函数
	 * @param string $txt
	 * @param string $key
	 */
	public static function decrypt($txt){
	
		$txt = str_replace(' ', '+', $txt);
		$txt = base64_decode($txt);
		$key = self::getBytes(conf_common::$secret_key);
		$txt = self::getBytes($txt);
		$ctr=0;
		$tmp = array();
		foreach($txt as $k => $val){
			if ($ctr==count($key)) {
				$ctr=0;
			}
			$tmp[$k] = $val ^ $key[$ctr];
			$ctr++;
		}
		return self::toStr($tmp);
	}
	
	
	//===================================================================
	
	/**
	 * 不雅词过滤
	 * @param string $str
	 */
	public static function bad_word_check($str)
	{
		$string = '_' . $str;
		$text = 'badword.txt';
		$badword = file_get_contents ( $text );
		$badwordArr = explode ( "\r\n", $badword );
		foreach ( $badwordArr as $value ) {
			if ($value) {
				if (strpos ( $string, $value )) {
					return false;
				}
			}
		}
		return true;
	}
	
	
	/**
	 * curl 提交post请求
	 * @param string $url
	 * @param array $post_data
	 */
	public static function curl_post($url,$post_data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 200); //超时时间200毫秒
		$return	=	curl_exec($ch);
		if(curl_errno($ch)){
			self::log_error(__FILE__, __LINE__, curl_error($ch));
			class_util::error_response(101);
			return false;
		}
		curl_close($ch);
		return	$return;
	}
	
	//==============================================================================
	/**
	 * 记录日志
	 * @param string $file
	 * @param string $text
	 */
	public static function log_debug($text){
	
		if(conf_common::$log_report){
				
			file_put_contents("log/debug.txt", date("Y-m-d H:i:t")." |  ".$_SERVER['REMOTE_ADDR']." |  ".$text."\n",FILE_APPEND);
		}
	}
	
	/**
	 * 记录日志
	 * @param string $file
	 * @param string $text
	 */
	public static function log_error($file,$line,$msg){
	
		if(conf_common::$error_report){
				
			file_put_contents("log/error.txt", date("Y-m-d H:i:t")." |  ".$_SERVER['REMOTE_ADDR']." |  from ".$file." line ".$line." msg :".$msg."\n",FILE_APPEND);
		}
	}
}